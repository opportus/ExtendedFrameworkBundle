<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Context;

/**
 * The controller exception.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Context
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
class ControllerException extends \Exception implements ControllerResultInterface
{
    use ControllerResultTrait;

    /**
     * Constructs the controller exception.
     * 
     * @param int $statusCode
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @param throwable $previous
     */
    public function __construct(int $statusCode, $data = null, string $message = '', int $code = 0, \Throwable $previous = null)
    {
        $this->statusCode = $statusCode;
        $this->data = $data;

        parent::__construct($message, $code, $previous);
    }
}
