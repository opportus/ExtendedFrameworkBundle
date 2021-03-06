<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
