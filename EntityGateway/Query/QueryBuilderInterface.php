<?php

namespace Opportus\ExtendedFrameworkBundle\EntityGateway\Query;

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
     * @return Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface
     * @throws Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayException If the method is called when the query is already prepared
     */
    public function prepareQuery(): QueryBuilderInterface;

    /**
     * Sets the entity FQCN.
     *
     * @param string $entityFqcn
     * @return Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface
     * @throws Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayException If the method is called when the query is not prepared
     */
    public function setEntityFqcn(string $entityFqcn): QueryBuilderInterface;

    /**
     * Sets the criteria.
     *
     * @param string $criteria
     * @return Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface
     * @throws Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayException If the method is called when the query is not prepared
     */
    public function setCriteria(string $criteria): QueryBuilderInterface;

    /**
     * Sets the order.
     *
     * @param array $order
     * @return Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface
     * @throws Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayException If the method is called when the query is not prepared
     */
    public function setOrder(array $order): QueryBuilderInterface;

    /**
     * Sets the limit.
     *
     * @param int $limit
     * @return Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface
     * @throws Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayException If the method is called when the query is not prepared
     */
    public function setLimit(int $limit): QueryBuilderInterface;

    /**
     * Sets the offset.
     *
     * @param int $offset
     * @return Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface
     * @throws Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayException If the method is called when the query is not prepared
     */
    public function setOffset(int $offset): QueryBuilderInterface;

    /**
     * Builds the query.
     *
     * @return Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryInterface
     * @throws Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayException If the method is called when the query is not prepared
     */
    public function build(): QueryInterface;
}
