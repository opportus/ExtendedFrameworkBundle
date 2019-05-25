<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Context;

/**
 * The controller result trait.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Context
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
trait ControllerResultTrait
{
    /**
     * @var int $statusCode
     */
    private $statusCode;

    /**
     * @var mixed $data
     */
    private $data;

    /**
     * Gets the status code.
     * 
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Gets the data.
     * 
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
