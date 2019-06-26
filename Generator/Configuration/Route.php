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

use Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor\GetterAccessor;
use Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor\KeyAccessor;
use Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor\PropertyAccessor;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;

/**
 * The route configuration.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 *
 * @Annotation
 * @Target("ANNOTATION")
 */
final class Route extends AbstractValueConfiguration
{
    /**
     * @var string $name
     */
    private $name;

    /**
     * @var array $parameters
     */
    private $parameters;

    /**
     * Constructs the route configuration.
     *
     * @param array $values
     * @throws GeneratorException
     */
    public function __construct(array $values = [])
    {
        $this->name = $values['name'] ?? null;
        $this->parameters = $values['parameters'] ?? [];
        $options = $values['options'] ?? [];
        $strategyFqcn = $values['strategyFqcn'] ?? null;

        parent::__construct([
            'options' => $options,
            'strategyFqcn' => $strategyFqcn,
        ]);

        if (!\is_string($this->name)) {
            throw new GeneratorException(\sprintf(
                '"name" is expected to be a "string", got a "%s".',
                \gettype($this->name)
            ));
        }

        foreach ($this->parameters as $parameterKey => $parameterValue) {
            if (!\is_object($parameterValue)) {
                throw new GeneratorException(\sprintf(
                    'Parameter "%s" is expected to be an "object", got a "%s".',
                    $parameterKey,
                    \gettype($parameterValue)
                ));
            }

            if (!($parameterValue instanceof GetterAccessor || $parameterValue instanceof PropertyAccessor || $parameterValue instanceof KeyAccessor)) {
                throw new GeneratorException(\sprintf(
                    'Parameter "%s" is expected to be an instance of either "%s" or "%s" or "%s", got an instance of type "%s".',
                    $parameterKey,
                    GetterAccessor::class,
                    PropertyAccessor::class,
                    KeyAccessor::class,
                    \get_class($parameterValue)
                ));
            }
        }
    }

    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets the parameters.
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
