<?php

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
