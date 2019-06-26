<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\EntityGateway\Criteria;

/**
 * The criteria interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\EntityGateway\Criteria
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE MIT
 */
interface CriteriaInterface extends \IteratorAggregate, \ArrayAccess
{
    /**
     * Returns the criteria as expression.
     *
     * @param string $leftComparisonOperandPrefix
     * @return string
     */
    public function toString(string $leftComparisonOperandPrefix = ''): string;

    /**
     * Checks whether the criteria is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool;
}
