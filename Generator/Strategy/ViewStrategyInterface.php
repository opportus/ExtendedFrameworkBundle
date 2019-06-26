<?php

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
