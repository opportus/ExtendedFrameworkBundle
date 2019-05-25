<?php

namespace Opportus\ExtendedFrameworkBundle\Generator;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractValueConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The value generator interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface ValueGeneratorInterface
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
}
