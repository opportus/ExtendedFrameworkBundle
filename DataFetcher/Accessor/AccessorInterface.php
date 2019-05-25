<?php

namespace Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor;

/**
 * The accessor interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface AccessorInterface
{
    /**
     * Gets the name.
     * 
     * @return string
     */
    public function getName(): string;
}
