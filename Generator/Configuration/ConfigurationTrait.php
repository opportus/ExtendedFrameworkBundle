<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\Generator\Configuration;

/**
 * The configuration trait.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
trait ConfigurationTrait
{
    /**
     * @var array $options
     */
    private $options;

    /**
     * @var null|string $strategyFqcn
     */
    private $strategyFqcn;

    /**
     * Gets the options.
     * 
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Gets the strategy FQCN.
     * 
     * @return null|string
     */
    public function getStrategyFqcn(): ?string
    {
        return $this->strategyFqcn;
    }
}
