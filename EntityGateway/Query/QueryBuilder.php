<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\EntityGateway\Query;

use Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayException;

/**
 * The query builder.
 *
 * @package Opportus\ExtendedFrameworkBundle\EntityGateway\Query
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE MIT
 */
final class QueryBuilder implements QueryBuilderInterface
{
    /**
     * @var array $queryDefintion
     */
    private $queryDefinition;

    /**
     * Constructs the query builder.
     *
     * @param array $queryDefinition
     */
    public function __construct(array $queryDefinition = [])
    {
        $this->queryDefinition = $queryDefinition;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareQuery(): QueryBuilderInterface
    {
        if ($this->hasPreparedQuery()) {
            throw new EntityGatewayException(\sprintf(
                'Invalid "%s" operation: the query is already prepared.',
                __METHOD__
            ));
        }

        return new self([
            'entity_fqcn' => '',
            'criteria' => '',
            'order' => [],
            'limit' => 1,
            'offset' => 0
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setEntityFqcn(string $entityFqcn): QueryBuilderInterface
    {
        if (!$this->hasPreparedQuery()) {
            throw new EntityGatewayException(\sprintf(
                'Invalid "%s" operation: the query should be prepared with "%s" prior to call this method.',
                __METHOD__,
                self::class.'::prepareQuery'
            ));
        }

        $queryDefinition = $this->queryDefinition;
        $queryDefinition['entity_fqcn'] = $entityFqcn;

        return new self($queryDefinition);
    }

    /**
     * {@inheritdoc}
     */
    public function setCriteria(string $criteria): QueryBuilderInterface
    {
        if (!$this->hasPreparedQuery()) {
            throw new EntityGatewayException(\sprintf(
                'Invalid "%s" operation: the query should be prepared with "%s" prior to call this method.',
                __METHOD__,
                self::class.'::prepareQuery'
            ));
        }

        $queryDefinition = $this->queryDefinition;
        $queryDefinition['criteria'] = $criteria;

        return new self($queryDefinition);
    }

    /**
     * {@inheritdoc}
     */
    public function setOrder(array $order): QueryBuilderInterface
    {
        if (!$this->hasPreparedQuery()) {
            throw new EntityGatewayException(\sprintf(
                'Invalid "%s" operation: the query should be prepared with "%s" prior to call this method.',
                __METHOD__,
                self::class.'::prepareQuery'
            ));
        }

        $queryDefinition = $this->queryDefinition;
        $queryDefinition['order'] = $order;

        return new self($queryDefinition);
    }

    /**
     * {@inheritdoc}
     */
    public function setLimit(int $limit): QueryBuilderInterface
    {
        if (!$this->hasPreparedQuery()) {
            throw new EntityGatewayException(\sprintf(
                'Invalid "%s" operation: the query should be prepared with "%s" prior to call this method.',
                __METHOD__,
                self::class.'::prepareQuery'
            ));
        }

        $queryDefinition = $this->queryDefinition;
        $queryDefinition['limit'] = $limit;

        return new self($queryDefinition);
    }

    /**
     * {@inheritdoc}
     */
    public function setOffset(int $offset): QueryBuilderInterface
    {
        if (!$this->hasPreparedQuery()) {
            throw new EntityGatewayException(\sprintf(
                'Invalid "%s" operation: the query should be prepared with "%s" prior to call this method.',
                __METHOD__,
                self::class.'::prepareQuery'
            ));
        }

        $queryDefinition = $this->queryDefinition;
        $queryDefinition['offset'] = $offset;

        return new self($queryDefinition);
    }

    /**
     * {@inheritdoc}
     */
    public function build(): QueryInterface
    {
        if (!$this->hasPreparedQuery()) {
            throw new EntityGatewayException(\sprintf(
                'Invalid "%s" operation: the query should be prepared with "%s" prior to call this method.',
                __METHOD__,
                self::class.'::prepareQuery'
            ));
        }

        return new Query(
            $this->queryDefinition['entity_fqcn'],
            $this->queryDefinition['criteria'],
            $this->queryDefinition['order'],
            $this->queryDefinition['limit'],
            $this->queryDefinition['offset']
        );
    }

    /**
     * Checks whether this has a prepared query.
     *
     * @return bool
     */
    private function hasPreparedQuery(): bool
    {
        return \array_keys($this->queryDefinition) === [
            'entity_fqcn',
            'criteria',
            'order',
            'limit',
            'offset'
        ];
    }
}
