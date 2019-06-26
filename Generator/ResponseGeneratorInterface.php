<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @param AbstractResponseConfiguration $responseConfiguration
     * @param ControllerResultInterface $controllerResult
     * @param Request $request
     * @return Response
     * @throws GeneratorException
     */
    public function generate(AbstractResponseConfiguration $responseConfiguration, ControllerResultInterface $controllerResult, Request $request): Response;
}
