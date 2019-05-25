<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Configuration;

use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The contextual configuration interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface ContextualConfigurationInterface extends ConfigurationInterface
{
    /**
     * Defines whether this configuration is in context.
     *
     * @param Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface $controllerResult
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    public function isInContext(ControllerResultInterface $controllerResult, Request $request): bool;
}
