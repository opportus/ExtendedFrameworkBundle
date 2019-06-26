<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractValueConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Symfony\Component\HttpFoundation\Request;

/**
 * The value strategy interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Strategy
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface ValueStrategyInterface
{
    /**
     * Generates the value.
     *
     * @param AbstractValueConfiguration $valueConfiguration
     * @param ControllerResultInterface $controllerResult
     * @param Request $request
     * @return string
     * @throws GeneratorException
     */
    public function generate(AbstractValueConfiguration $valueConfiguration, ControllerResultInterface $controllerResult, Request $request): string;

    /**
     * Defines whether or not this value generator strategy supports the value configuration within the current context.
     *
     * @param AbstractValueConfiguration $valueConfiguration
     * @param ControllerResultInterface $controllerResult
     * @param Request $request
     * @return bool
     */
    public function supports(AbstractValueConfiguration $valueConfiguration, ControllerResultInterface $controllerResult, Request $request): bool;
}
