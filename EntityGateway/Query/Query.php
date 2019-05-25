<?php

namespace Opportus\ExtendedFrameworkBundle\EntityGateway\Query;

use Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayException;
use Opportus\ExtendedFrameworkBundle\EntityGateway\Criteria\CriteriaInterface;
use Opportus\ExtendedFrameworkBundle\EntityGateway\Criteria\Criteria;
use Opportus\ExtendedFrameworkBundle\EntityGateway\Criteria\LeftComparisonOperandToken;

/**
 * The query.
 *
 * @package Opportus\ExtendedFrameworkBundle\EntityGateway\Query
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE MIT
 */
final class Query implements QueryInterface
{
    const ASC_SORT_ORDER  = 'ASC';
    const DESC_SORT_ORDER = 'DESC';

    /**
     * @var string $entityFqcn
     */
    private $entityFqcn;

    /**
     * @var Opportus\ExtendedFrameworkBundle\EntityGateway\Criteria\CriteriaInterface $criteria
     */
    private $criteria;

    /**
     * @var array $order
     */
    private $order;

    /**
     * @var int $limit
     */
    private $limit;

    /**
     * @var int $offset
     */
    private $offset;
    
    /**
     * Constructs the query.
     *
     * @param string $entityFqcn
     * @param string $criteria
     * @param array $order
     * @param int $limit
     * @param int $offset
     */
    public function __construct(string $entityFqcn, string $criteria, array $order, int $limit, int $offset)
    {
        if (empty($entityFqcn) || !\class_exists($entityFqcn)) {
            throw new EntityGatewayException(
                \sprintf(
                    'Invalid "entityFqcn" argument: expecting it to be the fully qualified name of an existing class, got "%s".',
                    $entityFqcn
                )
            );
        }

        $entityClassReflection = new \ReflectionClass($entityFqcn);

        $criteria = new Criteria($criteria);
        foreach ($criteria as $token) {
            if ($token instanceof LeftComparisonOperandToken && !$entityClassReflection->hasProperty($token->getValue())) {
                throw new EntityGatewayException(
                    \sprintf(
                        'Invalid "criteria" argument: expecting a left comparison operand to be the name of a property belonging to the entity "%s", got "%s" as a left comparison operand.',
                        $entityFqcn,
                        $token->getValue()
                    )
                );
            }
        }

        foreach ($order as $entityPropertyName => $sortOrder) {
            if (!$entityClassReflection->hasProperty($entityPropertyName)) {
                throw new EntityGatewayException(
                    \sprintf(
                        'Invalid "order" argument: expecting an element key to be the name of a property belonging to the entity "%s", got "%s" as property name.',
                        $entityFqcn,
                        $entityPropertyName
                    )
                );
            }

            if (self::ASC_SORT_ORDER !== $sortOrder && self::DESC_SORT_ORDER !== $sortOrder) {
                throw new EntityGatewayException(
                    \sprintf(
                        'Invalid "order" argument: expecting an element value to be eigher "%s" or "%s", got "%s".',
                        self::ASC_SORT_ORDER,
                        self::DESC_SORT_ORDER,
                        $sortOrder
                    )
                );
            }
        }

        if ($limit < 1) {
            throw new EntityGatewayException(
                \sprintf(
                    'Invalid "limit" argument: expecting it to be an integer greater than "0", got "%s".',
                    $limit
                )
            );
        }

        if ($offset < 0) {
            throw new EntityGatewayException(
                \sprintf(
                    'Invalid "offset" argument: expecting it to be an integer equal or greater than "0", got "%s".',
                    $offset
                )
            );
        }

        $this->entityFqcn = $entityFqcn;
        $this->criteria = $criteria;
        $this->order = $order;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityFqcn(): string
    {
        return $this->entityFqcn;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getCriteria(): CriteriaInterface
    {
        return $this->criteria;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOrder(): array
    {
        return $this->order;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOffset(): int
    {
        return $this->offset;
    }
}
