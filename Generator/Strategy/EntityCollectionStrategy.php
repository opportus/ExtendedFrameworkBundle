<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractDataConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\EntityCollection as EntityCollectionConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerException;
use Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayInterface;
use Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The entity collection strategy.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Strategy
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class EntityCollectionStrategy implements DataStrategyInterface
{
    /**
     * @var Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayInterface $entityGateway
     */
    private $entityGateway;

    /**
     * @var Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface $queryBuilder
     */
    private $queryBuilder;

    /**
     * @var Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    private $validator;

    /**
     * Constructs the submitted entity strategy.
     *
     * @param Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayInterface $entityGateway
     * @param Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface $queryBuilder
     * @param Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function __construct(EntityGatewayInterface $entityGateway, QueryBuilderInterface $queryBuilder, ValidatorInterface $validator)
    {
        $this->entityGateway = $entityGateway;
        $this->queryBuilder = $queryBuilder;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     *
     * @throws Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerException On HTTP client errors
     */
    public function generate(AbstractDataConfiguration $dataConfiguration, Request $request): object
    {
        if (false === $this->supports($dataConfiguration, $request)) {
            throw new GeneratorException(\sprintf(
                '"%s" does not support the data configuration within the current context.',
                self::class
            ));
        }

        if (null !== $constraintFqcn = $dataConfiguration->getQueryConstraintFqcn()) {
            $constraintViolationList = $this->validator->validate($request->query->all(), new $constraintFqcn());

            if (0 !== \count($constraintViolationList)) {
                throw new ControllerException(Response::HTTP_BAD_REQUEST, $constraintViolationList, (string)$constraintViolationList);
            }
        }

        $entityFqcn = $dataConfiguration->getEntityFqcn();
        $criteria = $request->query->get('criteria', []);
        $order = $request->query->get('order', []);
        $limit = $request->query->getInt('limit', 10);
        $offset = $request->query->getInt('offset', 0);

        $serializedCriteria = '';
        foreach ($criteria as $entityPropertyName => $entityPropertyValue) {
            $entityPropertyValue = \is_numeric($entityPropertyValue) ? $entityPropertyValue : \sprintf('"%s"', $entityPropertyValue);

            if ($serializedCriteria) {
                $serializedCriteria .= ' AND ';
            }

            $serializedCriteria .= \sprintf('%s = %s', $entityPropertyName, $entityPropertyValue);
        }

        $queryResult = $this->entityGateway->query($this->queryBuilder
            ->prepareQuery()
            ->setEntityFqcn($entityFqcn)
            ->setCriteria($serializedCriteria)
            ->setOrder($order)
            ->setLimit($limit)
            ->setOffset($offset)
            ->build()
        );

        if ($queryResult->isEmpty()) {
            throw new ControllerException(Response::HTTP_NOT_FOUND);
        }

        return $queryResult;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(AbstractDataConfiguration $dataConfiguration, Request $request): bool
    {
        if (!$dataConfiguration instanceof EntityCollectionConfiguration) {
            return false;
        }

        if (null !== $dataConfiguration->getStrategyFqcn() && self::class !== $dataConfiguration->getStrategyFqcn()) {
            return false;
        }

        return true;
    }
}
