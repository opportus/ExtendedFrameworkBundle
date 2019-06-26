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
     * @param AbstractFlashConfiguration $flashConfiguration
     * @param ControllerResultInterface $controllerResult
     * @param Request $request
     * @throws GeneratorException
     */
    public function generate(AbstractFlashConfiguration $flashConfiguration, ControllerResultInterface $controllerResult, Request $request);
}
