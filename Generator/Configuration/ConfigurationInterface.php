<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Configuration;

/**
 * The configuration interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface ConfigurationInterface
{
    /**
     * Gets the options.
     * 
     * @return array
     */
    public function getOptions(): array;

    /**
     * Gets the strategy FQCN.
     *
     * @return null|string
     */
    public function getStrategyFqcn(): ?string;
}
