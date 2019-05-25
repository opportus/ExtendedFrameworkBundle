<?php

namespace Opportus\ExtendedFrameworkBundle\Generator;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractFlashConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The flash generator interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface FlashGeneratorInterface
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
}
