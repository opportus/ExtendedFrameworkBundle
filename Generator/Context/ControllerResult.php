<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Context;

/**
 * The controller result.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Context
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
class ControllerResult implements ControllerResultInterface
{
    use ControllerResultTrait;
    
    /**
     * Constructs the controller result.
     *
     * @param int $statusCode
     * @param mixed $data
     */
    public function __construct(int $statusCode, $data = null)
    {
        $this->statusCode = $statusCode;
        $this->data = $data;
    }
}
