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

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractViewConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Symfony\Component\HttpFoundation\Request;

/**
 * The view strategy interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Strategy
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface ViewStrategyInterface
{
    /**
     * Generates the view.
     *
     * @param AbstractViewConfiguration $viewConfiguration
     * @param ControllerResultInterface $controllerResult
     * @param Request $request
     * @return string
     * @throws GeneratorException
     */
    public function generate(AbstractViewConfiguration $viewConfiguration, ControllerResultInterface $controllerResult, Request $request): string;

    /**
     * Defines whether or not this view generator strategy supports the view configuration within the current context.
     *
     * @param AbstractViewConfiguration $viewConfiguration
     * @param ControllerResultInterface $controllerResult
     * @param Request $request
     * @return bool
     */
    public function supports(AbstractViewConfiguration $viewConfiguration, ControllerResultInterface $controllerResult, Request $request): bool;
}
