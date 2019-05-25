<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractDataConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\Entity as EntityConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerException;
use Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayInterface;
use Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The entity strategy.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Strategy
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class EntityStrategy implements DataStrategyInterface
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
     * Constructs the submitted entity strategy.
     *
     * @param Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayInterface $entityGateway
     * @param Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface $queryBuilder
     */
    public function __construct(EntityGatewayInterface $entityGateway, QueryBuilderInterface $queryBuilder)
    {
        $this->entityGateway = $entityGateway;
        $this->queryBuilder = $queryBuilder;
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

        $entityFqcn = $dataConfiguration->getEntityFqcn();
        $entityIdKey = $dataConfiguration->getEntityId() ?? 'id';
        $entityIdValue = $request->attributes->get($entityIdKey);

        if (null === $entityIdValue) {
            throw new GeneratorException(\sprintf(
                'The request does not contain any attribute "%s" in order to identify the requested entity.',
                $entityIdKey
            ));
        }

        $entityIdValue = \is_numeric($entityIdValue) ? $entityIdValue : \sprintf('"%s"', $entityIdValue);

        $queryResult = $this->entityGateway->query($this->queryBuilder
            ->prepareQuery()
            ->setEntityFqcn($entityFqcn)
            ->setCriteria(\sprintf('%s = %s', $entityIdKey, $entityIdValue))
            ->build()
        );

        if ($queryResult->isEmpty()) {
            throw new ControllerException(Response::HTTP_NOT_FOUND);
        }

        return $queryResult[0];
    }

    /**
     * {@inheritdoc}
     */
    public function supports(AbstractDataConfiguration $dataConfiguration, Request $request): bool
    {
        if (!$dataConfiguration instanceof EntityConfiguration) {
            return false;
        }

        if (null !== $dataConfiguration->getStrategyFqcn() && self::class !== $dataConfiguration->getStrategyFqcn()) {
            return false;
        }

        return true;
    }
}
