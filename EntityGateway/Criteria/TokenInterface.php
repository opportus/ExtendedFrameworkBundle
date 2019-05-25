<?php

namespace Opportus\ExtendedFrameworkBundle\EntityGateway\Criteria;

/**
 * The token interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\EntityGateway\Criteria
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE MIT
 */
interface TokenInterface
{
    /**
     * Gets the value.
     *
     * @return string
     */
    public function getValue(): string;
}
