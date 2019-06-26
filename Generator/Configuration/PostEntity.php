<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Configuration;

use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;

/**
 * The post entity configuration.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 *
 * @Annotation
 * @Target("METHOD")
 */
final class PostEntity extends AbstractDataConfiguration
{
    /**
     * @var null|string $validationFqcn
     */
    private $validationFqcn;

    /**
     * @var null|string $deserializationFqcn
     */
    private $deserializationFqcn;

    /**
     * @var array $deserializationContext
     */
    private $deserializationContext;

    /**
     * Constructs the post entity configuration.
     *
     * @param array $values
     * @throws GeneratorException
     */
    public function __construct(array $values = [])
    {
        $this->validationFqcn = $values['validationFqcn'] ?? null;
        $this->deserializationFqcn = $values['deserializationFqcn'] ?? null;
        $this->deserializationContext = $values['deserializationContext'] ?? [];
        $entityFqcn = $values['entityFqcn'] ?? null;
        $options = $values['options'] ?? [];
        $strategyFqcn = $values['strategyFqcn'] ?? null;

        parent::__construct([
            'entityFqcn' => $entityFqcn,
            'options' => $options,
            'strategyFqcn' => $strategyFqcn,
        ]);

        if (null !== $this->validationFqcn) {
            if (!\is_string($this->validationFqcn)) {
                throw new GeneratorException(\sprintf(
                    '"validationFqcn" is expected to be a "string", got a "%s".',
                    \gettype($this->validationFqcn)
                ));
            }

            if (!\class_exists($this->validationFqcn)) {
                throw new GeneratorException(\sprintf(
                    '"validationFqcn" is expected to be a Fully Qualified Class Name, got class "%s" which does not exist.',
                    $this->validationFqcn
                ));
            }
        }

        if (null !== $this->deserializationFqcn) {
            if (!\is_string($this->deserializationFqcn)) {
                throw new GeneratorException(\sprintf(
                    '"deserializationFqcn" is expected to be a "string", got a "%s".',
                    \gettype($this->deserializationFqcn)
                ));
            }

            if (!\class_exists($this->deserializationFqcn)) {
                throw new GeneratorException(\sprintf(
                    '"deserializationFqcn" is expected to be a Fully Qualified Class Name, got class "%s" which does not exist.',
                    $this->deserializationFqcn
                ));
            }
        }

        if (!\is_array($this->deserializationContext)) {
            throw new GeneratorException(\sprintf(
                '"deserializationContext" is expected to be an array, got a "%s".',
                \gettype($this->deserializationContext)
            ));
        }
    }

    /**
     * Gets the validation FQCN.
     *
     * @return null|string
     */
    public function getValidationFqcn(): ?string
    {
        return $this->validationFqcn;
    }

    /**
     * Gets the deserialization FQCN.
     *
     * @return null|string
     */
    public function getDeserializationFqcn(): ?string
    {
        return $this->deserializationFqcn;
    }

    /**
     * Gets the deserialization context.
     *
     * @return array
     */
    public function getDeserializationContext(): array
    {
        return $this->deserializationContext;
    }
}
