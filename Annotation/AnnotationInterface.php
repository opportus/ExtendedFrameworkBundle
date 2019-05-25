<?php

namespace Opportus\ExtendedFrameworkBundle\Annotation;

/**
 * The annotation interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\Annotation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface AnnotationInterface
{
    /**
     * Gets the annotation alias.
     * 
     * @return string
     */
    public function getAnnotationAlias(): string;
}
