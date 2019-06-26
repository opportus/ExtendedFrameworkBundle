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

use Opportus\ExtendedFrameworkBundle\Annotation\AnnotationInterface;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;

/**
 * The abstract data configuration.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
abstract class AbstractDataConfiguration implements ConfigurationInterface, AnnotationInterface
{
    use ConfigurationTrait;

    /**
     * @var string $entityFqcn
     */
    private $entityFqcn;

    /**
     * Constructs the data configuration.
     *
     * @param array $values
     * @throws GeneratorException
     */
    public function __construct(array $values = [])
    {
        $this->entityFqcn = $values['entityFqcn'] ?? null;
        $this->options = $values['options'] ?? [];
        $this->strategyFqcn = $values['strategyFqcn'] ?? null;

        if (!\is_string($this->entityFqcn)) {
            throw new GeneratorException(\sprintf(
                '"entityFqcn" is expected to be a "string", got a "%s".',
                \gettype($this->entityFqcn)
            ));
        }

        if (!\class_exists($this->entityFqcn)) {
            throw new GeneratorException(\sprintf(
                '"entityFqcn" is expected to be a Fully Qualified Class Name, got class "%s" which does not exist.',
                $this->entityFqcn
            ));
        }

        if (!\is_array($this->options)) {
            throw new GeneratorException(\sprintf(
                '"options" is expected to be an "array", got a "%s".',
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
    public function getAnnotationAlias(): string
    {
        return 'data';
    }

    /**
     * Gets the entity FQCN.
     * 
     * @return string
     */
    public function getEntityFqcn(): string
    {
        return $this->entityFqcn;
    }
}
