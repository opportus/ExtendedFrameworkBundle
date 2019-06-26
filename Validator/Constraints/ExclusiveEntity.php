<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * The exclusive entity constraint.
 *
 * @package Opportus\ExtendedFrameworkBundle\Validator\Constraints
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
class ExclusiveEntity extends Constraint
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
    public $message = '%key% "%value%" is not exclusive.';

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
