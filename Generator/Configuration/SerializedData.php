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
 * The serialized data configuration.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 *
 * @Annotation
 * @Target("ANNOTATION")
 */
final class SerializedData extends AbstractViewConfiguration
{
    /**
     * @var null|string $serializationFqcn
     */
    private $serializationFqcn;
    
    /**
     * @var array $serializationContext
     */
    private $serializationContext;

    /**
     * @var null|AccessorInterface $accessor
     */
    private $accessor;

    /**
     * Constructs the serialized data configuration.
     *
     * @param array $values
     * @throws GeneratorException
     */
    public function __construct(array $values = [])
    {
        $this->serializationFqcn = $values['serializationFqcn'] ?? null;
        $this->serializationContext = $values['serializationContext'] ?? [];
        $this->acessor = $values['accessor'] ?? null;
        $format = $values['format'] ?? null;
        $options = $values['options'] ?? [];
        $strategyFqcn = $values['strategyFqcn'] ?? null;

        parent::__construct([
            'format' => $format,
            'options' => $options,
            'strategyFqcn' => $strategyFqcn,
        ]);

        if (null !== $this->serializationFqcn) {
            if (!\is_string($this->serializationFqcn)) {
                throw new GeneratorException(\sprintf(
                    '"serializationFqcn" is expected to be a "string", got a "%s".',
                    \gettype($this->serializationFqcn)
                ));
            }

            if (!\class_exists($this->serializationFqcn)) {
                throw new GeneratorException(\sprintf(
                    '"serializationFqcn" is expected to be a Fully Qualified Class Name, got class "%s" which does not exist.',
                    $this->serializationFqcn
                ));
            }
        }

        if (!\is_array($this->serializationContext)) {
            throw new GeneratorException(\sprintf(
                '"serializationContext" is expected to be an array, got a "%s".',
                \gettype($this->serializationContext)
            ));
        }

        if (null !== $this->accessor) {
            if (!\is_object($this->accessor)) {
                throw new GeneratorException(\sprintf(
                    '"dataAccessor" is expected to be an "object", got a "%s".',
                    \gettype($this->accessor)
                ));
            }

            if (!($this->accessor instanceof GetterAccessor || $this->accessor instanceof PropertyAccessor || $this->accessor instanceof KeyAccessor)) {
                throw new GeneratorException(\sprintf(
                    '"accessor" is expected to be an instance of either "%s" or "%s" or "%s", got an instance of type "%s".',
                    GetterAccessor::class,
                    PropertyAccessor::class,
                    KeyAccessor::class,
                    \get_class($this->accessor)
                ));
            }
        }
    }

    /**
     * Gets the serialization FQCN.
     *
     * @return null|string
     */
    public function getSerializationFqcn(): ?string
    {
        return $this->serializationFqcn;
    }

    /**
     * Gets the serialization context.
     *
     * @return array
     */
    public function getSerializationContext(): array
    {
        return $this->serializationContext;
    }

    /**
     * Gets the accessor.
     *
     * @return null|AccessorInterface
     */
    public function getAccessor(): ?AccessorInterface
    {
        return $this->accessor;
    }
}
