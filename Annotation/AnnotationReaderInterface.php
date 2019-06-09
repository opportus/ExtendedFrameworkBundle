<?php

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
