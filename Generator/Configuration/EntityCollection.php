<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Configuration;

use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;

/**
 * The entity collection configuration.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 * 
 * @Annotation
 * @Target("METHOD")
 */
final class EntityCollection extends AbstractDataConfiguration
{
    /**
     * @var null|string $queryConstraintFqcn
     */
    private $queryConstraintFqcn;

    /**
     * Constructs the entity collection configuration.
     *
     * @param array $values
     * @throws Opportus\ExtendedFrameworkBundle\Generator\GeneratorException
     */
    public function __construct(array $values = [])
    {
        $this->queryConstraintFqcn = $values['queryConstraintFqcn'] ?? null;
        $entityFqcn = $values['entityFqcn'] ?? null;
        $options = $values['options'] ?? [];
        $strategyFqcn = $values['strategyFqcn'] ?? null;

        parent::__construct([
            'entityFqcn' => $entityFqcn,
            'options' => $options,
            'strategyFqcn' => $strategyFqcn,
        ]);

        if (null !== $this->queryConstraintFqcn) {
            if (!\is_string($this->queryConstraintFqcn)) {
                throw new GeneratorException(\sprintf(
                    '"queryConstraintFqcn" is expected to be a "string", got a "%s".',
                    \gettype($this->queryConstraintFqcn)
                ));
            }

            if (!\class_exists($this->queryConstraintFqcn)) {
                throw new GeneratorException(\sprintf(
                    '"queryConstraintFqcn" is expected to be a Fully Qualified Class Name, got class "%s" which does not exist.',
                    $this->queryConstraintFqcn
                ));
            }
        }
    }

    /**
     * Gets the query constraint FQCN.
     * 
     * @return null|string
     */
    public function getQueryConstraintFqcn(): ?string
    {
        return $this->queryConstraintFqcn;
    }
}
