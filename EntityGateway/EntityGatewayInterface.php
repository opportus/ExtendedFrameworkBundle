<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\EntityGateway;

use Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryInterface;
use Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryResultInterface;

/**
 * The entity gateway interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\EntityGateway
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE MIT
 */
interface EntityGatewayInterface
{
    /**
     * Queries entities.
     *
     * @param QueryInterface $query
     * @return QueryResultInterface
     * @throws EntityGatewayException If the persistence layer throws an exception
     */
    public function query(QueryInterface $query): QueryResultInterface;

    /**
     * Saves an entity.
     *
     * @param object $entity
     * @throws EntityGatewayException If the persistence layer throws an exception
     */
    public function save(object $entity);

    /**
     * Deletes an entity.
     *
     * @param object $entity
     * @throws EntityGatewayException If the persistence layer throws an exception
     */
    public function delete(object $entity);

    /**
     * Commits entities state.
     *
     * @throws EntityGatewayException If the persistence layer throws an exception
     */
    public function commit();
}
