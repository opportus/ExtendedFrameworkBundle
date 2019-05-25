<?php

namespace Opportus\ExtendedFrameworkBundle\Generator;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractResponseConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The response generator interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface ResponseGeneratorInterface
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
}
