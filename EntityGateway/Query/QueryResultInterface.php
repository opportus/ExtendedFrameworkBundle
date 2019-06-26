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

/**
 * The query result interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\EntityGateway\Query
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE MIT
 */
interface QueryResultInterface extends \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * Returns the query result as array.
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Checks whether the query result is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool;
}
