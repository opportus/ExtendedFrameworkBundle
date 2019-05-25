<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Context;

/**
 * The controller result interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Context
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface ControllerResultInterface
{
    /**
     * Gets the status code.
     * 
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * Gets the data.
     * 
     * @return mixed
     */
    public function getData();
}
