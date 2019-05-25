<?php

namespace Opportus\ExtendedFrameworkBundle\Generator;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractViewConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The view generator interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface ViewGeneratorInterface
{
    /**
     * Generates the view.
     * 
     * @param Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractViewConfiguration $viewConfiguration
     * @param Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface $controllerResult
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return string
     * @throws Opportus\ExtendedFrameworkBundle\Generator\GeneratorException
     */
    public function generate(AbstractViewConfiguration $viewConfiguration, ControllerResultInterface $controllerResult, Request $request): string;
}
