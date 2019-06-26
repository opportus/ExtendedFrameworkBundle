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

use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Symfony\Component\HttpFoundation\Request;

/**
 * The abstract view configuration.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
abstract class AbstractViewConfiguration implements ContextualConfigurationInterface
{
    use ConfigurationTrait;

    /**
     * @var string $format
     */
    private $format;

    /**
     * Constructs the view configuration.
     *
     * @param array $values
     * @throws GeneratorException
     */
    public function __construct(array $values = [])
    {
        $this->format = $values['format'] ?? null;
        $this->options = $values['options'] ?? [];
        $this->strategyFqcn = $values['strategyFqcn'] ?? null;

        if (!\is_string($this->format)) {
            throw new GeneratorException(\sprintf(
                '"format" is expected to be a "string", got a "%s".',
                \gettype($this->format)
            ));
        }

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
    public function getAnnotationAlias(): string
    {
        return 'view';
    }

    /**
     * Gets the format.
     *
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * {@inheritdoc}
     */
    public function isInContext(ControllerResultInterface $controllerResult, Request $request): bool
    {
        return \in_array($this->getFormat(), $request->getAcceptableContentTypes()) || \in_array('*/*', $request->getAcceptableContentTypes());
    }
}
