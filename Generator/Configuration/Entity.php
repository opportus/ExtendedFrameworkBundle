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
     * @var string $entityId
     */
    private $entityId;

    /**
     * Constructs the entity configuration.
     *
     * @param array $values
     * @throws Opportus\ExtendedFrameworkBundle\Generator\GeneratorException
     */
    public function __construct(array $values = [])
    {
        $this->entityId = $values['entityId'] ?? 'id';
        $entityFqcn = $values['entityFqcn'] ?? null;
        $options = $values['options'] ?? [];
        $strategyFqcn = $values['strategyFqcn'] ?? null;

        parent::__construct([
            'entityFqcn' => $entityFqcn,
            'options' => $options,
            'strategyFqcn' => $strategyFqcn,
        ]);

        if (!\is_string($this->entityId)) {
            throw new GeneratorException(\sprintf(
                '"entityId" is expected to be a "string", got a "%s".',
                \gettype($this->entityId)
            ));
        }
    }

    /**
     * Gets the entity ID.
     * 
     * @return string
     */
    public function getEntityId(): string
    {
        return $this->entityId;
    }
}
