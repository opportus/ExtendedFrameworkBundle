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

use Opportus\ExtendedFrameworkBundle\EntityGateway\Criteria\CriteriaInterface;

/**
 * The query interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\EntityGateway\Query
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE MIT
 */
interface QueryInterface
{
    /**
     * Gets the entity FQCN.
     *
     * @return string
     */
    public function getEntityFqcn(): string;
    
    /**
     * Gets the criteria.
     *
     * @return CriteriaInterface
     */
    public function getCriteria(): CriteriaInterface;
    
    /**
     * Gets the order.
     *
     * @return array
     */
    public function getOrder(): array;
    
    /**
     * Gets the limit.
     *
     * @return int The maximum number of entities to query for
     */
    public function getLimit(): int;
    
    /**
     * Gets the offset.
     *
     * @return int The number of entities to omit from the query
     */
    public function getOffset(): int;
}
