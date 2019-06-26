<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Configuration;

use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;

/**
 * The entity configuration.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 *
 * @Annotation
 * @Target("METHOD")
 */
final class Entity extends AbstractDataConfiguration
{
    /**
     * @var array $entityCriteria
     */
    private $entityCriteria;

    /**
     * Constructs the entity configuration.
     *
     * @param array $values
     * @throws GeneratorException
     */
    public function __construct(array $values = [])
    {
        $this->entityCriteria = $values['entityCriteria'] ?? ['id' => 'id'];
        $entityFqcn = $values['entityFqcn'] ?? null;
        $options = $values['options'] ?? [];
        $strategyFqcn = $values['strategyFqcn'] ?? null;

        parent::__construct([
            'entityFqcn' => $entityFqcn,
            'options' => $options,
            'strategyFqcn' => $strategyFqcn,
        ]);

        if (!\is_array($this->entityCriteria) || empty($this->entityCriteria)) {
            throw new GeneratorException(\sprintf(
                '"entityCriteria" is expected to be a non empty "array", got "%s".',
                \gettype($this->entityCriteria) === 'array' ? 'empty array' : \gettype($this->entityCriteria)
            ));
        }
    }

    /**
     * Gets the entity criteria.
     *
     * @return array
     */
    public function getEntityCriteria(): array
    {
        return $this->entityCriteria;
    }
}
