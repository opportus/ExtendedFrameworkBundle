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

use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;

/**
 * The abstract value configuration.
 *
 * The abstract value configuration represents a value that will be generated at runtime.
 * Extend from this a specific value generator configuration such as `Route` or `Trans`.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
abstract class AbstractValueConfiguration implements ConfigurationInterface
{
    use ConfigurationTrait;

    /**
     * Constructs the value configuration.
     *
     * @param array $values
     * @throws GeneratorException
     */
    public function __construct(array $values = [])
    {
        $this->options = $values['options'] ?? [];
        $this->strategyFqcn = $values['strategyFqcn'] ?? null;

        if (!\is_array($this->options)) {
            throw new GeneratorException(\sprintf(
                '"options" is expected to be an array, got a "%s".',
                \gettype($this->options)
            ));
        }

        if (null !== $this->strategyFqcn) {
            if (!\is_string($this->strategyFqcn)) {
                throw new GeneratorException(\sprintf(
                    '"strategyFqcn" is expected to be a "string", got a "%s".',
                    \gettype($this->strategyFqcn)
                ));
            }

            if (!\class_exists($this->strategyFqcn)) {
                throw new GeneratorException(\sprintf(
                    '"strategyFqcn" is expected to be a Fully Qualified Class Name, got class "%s" which does not exist.',
                    $this->strategyFqcn
                ));
            }
        }
    }

    /**
     * Gets the annotation alias.
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'value';
    }
}
