<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractResponseConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
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
     * @param AbstractResponseConfiguration $responseConfiguration
     * @param ControllerResultInterface $controllerResult
     * @param Request $request
     * @return Response
     * @throws GeneratorException
     */
    public function generate(AbstractResponseConfiguration $responseConfiguration, ControllerResultInterface $controllerResult, Request $request): Response;

    /**
     * Defines whether or not this response generator strategy supports the response configuration within the current context.
     *
     * @param AbstractResponseConfiguration $responseConfiguration
     * @param ControllerResultInterface $controllerResult
     * @param Request $request
     * @return bool
     */
    public function supports(AbstractResponseConfiguration $responseConfiguration, ControllerResultInterface $controllerResult, Request $request): bool;
}
