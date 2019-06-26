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

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractFlashConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Symfony\Component\HttpFoundation\Request;

/**
 * The flash strategy interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Strategy
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface FlashStrategyInterface
{
    /**
     * Generates the flash.
     *
     * @param AbstractFlashConfiguration $flashConfiguration
     * @param ControllerResultInterface $controllerResult
     * @param Request $request
     * @throws GeneratorException
     */
    public function generate(AbstractFlashConfiguration $flashConfiguration, ControllerResultInterface $controllerResult, Request $request);

    /**
     * Defines whether or not this flash generator strategy supports the flash configuration within the current context.
     *
     * @param AbstractFlashConfiguration $flashConfiguration
     * @param ControllerResultInterface $controllerResult
     * @param Request $request
     * @return bool
     */
    public function supports(AbstractFlashConfiguration $flashConfiguration, ControllerResultInterface $controllerResult, Request $request): bool;
}
