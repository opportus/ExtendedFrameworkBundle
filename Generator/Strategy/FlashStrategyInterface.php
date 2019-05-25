<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractFlashConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
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
     * @param Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractFlashConfiguration $flashConfiguration
     * @param Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface $controllerResult
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @throws Opportus\ExtendedFrameworkBundle\Generator\GeneratorException
     */
    public function generate(AbstractFlashConfiguration $flashConfiguration, ControllerResultInterface $controllerResult, Request $request);

    /**
     * Defines whether or not this flash generator strategy supports the flash configuration within the current context.
     * 
     * @param Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractFlashConfiguration $flashConfiguration
     * @param Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface $controllerResult
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    public function supports(AbstractFlashConfiguration $flashConfiguration, ControllerResultInterface $controllerResult, Request $request): bool;
}
