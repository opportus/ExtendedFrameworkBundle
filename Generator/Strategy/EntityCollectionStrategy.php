<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayInterface;
use Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractDataConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\EntityCollection as EntityCollectionConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerException;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @var EntityGatewayInterface $entityGateway
     */
    private $entityGateway;

    /**
     * @var QueryBuilderInterface $queryBuilder
     */
    private $queryBuilder;

    /**
     * @var ValidatorInterface $validator
     */
    private $validator;

    /**
     * Constructs the submitted entity strategy.
     *
     * @param EntityGatewayInterface $entityGateway
     * @param QueryBuilderInterface $queryBuilder
     * @param ValidatorInterface $validator
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
     * @throws ControllerException On HTTP client errors
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

        $criteria = [];

        foreach ($dataConfiguration->getEntityCriteria() as $entityPropertyName => $entityPropertyValueKey) {
            $criteria[$entityPropertyName] = $request->attributes->get($entityPropertyValueKey);
        }

        $criteria = \array_merge($request->query->get('criteria', []), $criteria);

        $serializedCriteria = '';

        foreach ($criteria as $entityPropertyName => $entityPropertyValue) {
            if ($serializedCriteria) {
                $serializedCriteria .= ' AND ';
            }

            $entityPropertyValue = \is_numeric($entityPropertyValue) ? $entityPropertyValue : \sprintf('"%s"', $entityPropertyValue);
            $serializedCriteria .= \sprintf('%s = %s', $entityPropertyName, $entityPropertyValue);
        }

        $entityFqcn = $dataConfiguration->getEntityFqcn();
        $order = $request->query->get('order', []);
        $limit = $request->query->getInt('limit', 10);
        $offset = $request->query->getInt('offset', 0);

        $queryResult = $this->entityGateway->query(
            $this->queryBuilder
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
