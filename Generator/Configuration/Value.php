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
 * The value configuration.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 *
 * @Annotation
 * @Target("ANNOTATION")
 */
final class Value extends AbstractValueConfiguration
{
    /**
     * @var string $id
     */
    private $value;

    /**
     * @var array $parameters
     */
    private $parameters;

    /**
     * Constructs the value configuration.
     *
     * @param array $values
     * @throws GeneratorException
     */
    public function __construct(array $values = [])
    {
        $this->value = $values['value'] ?? null;
        $this->parameters = $values['parameters'] ?? [];
        $options = $values['options'] ?? [];
        $strategyFqcn = $values['strategyFqcn'] ?? null;

        parent::__construct([
            'options' => $options,
            'strategyFqcn' => $strategyFqcn,
        ]);

        if (!\is_string($this->value)) {
            throw new GeneratorException(\sprintf(
                '"value" is expected to be a "string", got a "%s".',
                \gettype($this->value)
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
     * Gets the value.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
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
