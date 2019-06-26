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
 * The query builder interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\EntityGateway\Query
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE MIT
 */
interface QueryBuilderInterface
{
    /**
     * Prepares a query.
     *
     * @return QueryBuilderInterface
     * @throws EntityGatewayException If the method is called when the query is already prepared
     */
    public function prepareQuery(): QueryBuilderInterface;

    /**
     * Sets the entity FQCN.
     *
     * @param string $entityFqcn
     * @return QueryBuilderInterface
     * @throws EntityGatewayException If the method is called when the query is not prepared
     */
    public function setEntityFqcn(string $entityFqcn): QueryBuilderInterface;

    /**
     * Sets the criteria.
     *
     * @param string $criteria
     * @return QueryBuilderInterface
     * @throws EntityGatewayException If the method is called when the query is not prepared
     */
    public function setCriteria(string $criteria): QueryBuilderInterface;

    /**
     * Sets the order.
     *
     * @param array $order
     * @return QueryBuilderInterface
     * @throws EntityGatewayException If the method is called when the query is not prepared
     */
    public function setOrder(array $order): QueryBuilderInterface;

    /**
     * Sets the limit.
     *
     * @param int $limit
     * @return QueryBuilderInterface
     * @throws EntityGatewayException If the method is called when the query is not prepared
     */
    public function setLimit(int $limit): QueryBuilderInterface;

    /**
     * Sets the offset.
     *
     * @param int $offset
     * @return QueryBuilderInterface
     * @throws EntityGatewayException If the method is called when the query is not prepared
     */
    public function setOffset(int $offset): QueryBuilderInterface;

    /**
     * Builds the query.
     *
     * @return QueryInterface
     * @throws EntityGatewayException If the method is called when the query is not prepared
     */
    public function build(): QueryInterface;
}
