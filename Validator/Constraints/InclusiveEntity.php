<?php

namespace Opportus\ExtendedFrameworkBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * The inclusive entity constraint.
 *
 * @package Opportus\ExtendedFrameworkBundle\Validator\Constraints
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
class InclusiveEntity extends Constraint
{
    /**
     * @var string $entityFqcn
     */
    public $entityFqcn;

    /**
     * @var string $key
     */
    public $key;

    /**
     * @var string $message
     */
    public $message = '%key% "%value%" is not inclusive.';

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions()
    {
        return [
            'entityFqcn',
            'key',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
