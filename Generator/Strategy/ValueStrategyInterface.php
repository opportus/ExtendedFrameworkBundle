<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractValueConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
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
     * @param Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractValueConfiguration $valueConfiguration
     * @param Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface $controllerResult
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return string
     * @throws Opportus\ExtendedFrameworkBundle\Generator\GeneratorException
     */
    public function generate(AbstractValueConfiguration $valueConfiguration, ControllerResultInterface $controllerResult, Request $request): string;

    /**
     * Defines whether or not this value generator strategy supports the value configuration within the current context.
     * 
     * @param Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractValueConfiguration $valueConfiguration
     * @param Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface $controllerResult
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    public function supports(AbstractValueConfiguration $valueConfiguration, ControllerResultInterface $controllerResult, Request $request): bool;
}
