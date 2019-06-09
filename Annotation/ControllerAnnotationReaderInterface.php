<?php

namespace Opportus\ExtendedFrameworkBundle\Annotation;

use Symfony\Component\HttpFoundation\Request;

/**
 * The controller annotation reader interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\Annotation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface ControllerAnnotationReaderInterface
{
    /**
     * Gets the controller annotations.
     *
     * @param Request $request
     * @return array
     */
    public function getControllerAnnotations(Request $request): array;
}
