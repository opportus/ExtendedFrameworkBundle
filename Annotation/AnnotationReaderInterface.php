<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\Annotation;

use ReflectionMethod;

/**
 * The annotation reader interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\Annotation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface AnnotationReaderInterface
{
    /**
     * Gets the method annotations.
     *
     * @param ReflectionMethod $methodReflection
     * @return array
     */
    public function getMethodAnnotations(ReflectionMethod $methodReflection): array;
}
