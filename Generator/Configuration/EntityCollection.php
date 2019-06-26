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
     * @var array $entityCriteria
     */
    private $entityCriteria;

    /**
     * @var null|string $queryConstraintFqcn
     */
    private $queryConstraintFqcn;

    /**
     * Constructs the entity collection configuration.
     *
     * @param array $values
     * @throws GeneratorException
     */
    public function __construct(array $values = [])
    {
        $this->entityCriteria = $values['entityCriteria'] ?? [];
        $this->queryConstraintFqcn = $values['queryConstraintFqcn'] ?? null;
        $entityFqcn = $values['entityFqcn'] ?? null;
        $options = $values['options'] ?? [];
        $strategyFqcn = $values['strategyFqcn'] ?? null;

        parent::__construct([
            'entityFqcn' => $entityFqcn,
            'options' => $options,
            'strategyFqcn' => $strategyFqcn,
        ]);

        if (!\is_array($this->entityCriteria)) {
            throw new GeneratorException(\sprintf(
                '"entityCriteria" is expected to be an "array", got "%s".',
                \gettype($this->entityCriteria)
            ));
        }

        if (null !== $this->queryConstraintFqcn) {
            if (!\is_string($this->queryConstraintFqcn)) {
                throw new GeneratorException(\sprintf(
                    '"queryConstraintFqcn" is expected to be a "string", got "%s".',
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
     * Gets the entity criteria.
     *
     * @return array
     */
    public function getEntityCriteria(): array
    {
        return $this->entityCriteria;
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
