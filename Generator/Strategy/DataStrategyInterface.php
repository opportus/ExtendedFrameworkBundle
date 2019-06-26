<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractDataConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Symfony\Component\HttpFoundation\Request;

/**
 * The data strategy interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Strategy
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface DataStrategyInterface
{
    /**
     * Generates the data.
     *
     * @param AbstractDataConfiguration $dataConfiguration
     * @param Request $request
     * @return object
     * @throws GeneratorException
     */
    public function generate(AbstractDataConfiguration $dataConfiguration, Request $request): object;

    /**
     * Defines whether or not this data generator strategy supports the data configuration within the current context.
     *
     * @param AbstractDataConfiguration $dataConfiguration
     * @param Request $request
     * @return bool
     */
    public function supports(AbstractDataConfiguration $dataConfiguration, Request $request): bool;
}
