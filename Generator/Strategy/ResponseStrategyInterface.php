<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractResponseConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The response strategy interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Strategy
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface ResponseStrategyInterface
{
    /**
     * Generates the response.
     *
     * @param Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractResponseConfiguration $responseConfiguration
     * @param Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface $controllerResult
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     * @throws Opportus\ExtendedFrameworkBundle\Generator\GeneratorException
     */
    public function generate(AbstractResponseConfiguration $responseConfiguration, ControllerResultInterface $controllerResult, Request $request): Response;

    /**
     * Defines whether or not this response generator strategy supports the response configuration within the current context.
     * 
     * @param Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractResponseConfiguration $responseConfiguration
     * @param Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface $controllerResult
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    public function supports(AbstractResponseConfiguration $responseConfiguration, ControllerResultInterface $controllerResult, Request $request): bool;
}
