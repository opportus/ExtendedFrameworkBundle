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

use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

/**
 * The controller annotation reader.
 *
 * @package Opportus\ExtendedFrameworkBundle\Annotation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class ControllerAnnotationReader implements ControllerAnnotationReaderInterface
{
    /**
     * @var AnnotationReaderInterface $annotationReader
     */
    private $annotationReader;

    /**
     * @var ControllerResolverInterface $controllerResolver
     */
    private $controllerResolver;

    /**
     * Constructs the controller annotation reader.
     *
     * @param AnnotationReaderInterface $reader
     * @param ControllerResolverInterface $controllerResolver
     */
    public function __construct(AnnotationReaderInterface $annotationReader, ControllerResolverInterface $controllerResolver)
    {
        $this->annotationReader = $annotationReader;
        $this->controllerResolver = $controllerResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getControllerAnnotations(Request $request): array
    {
        $controller = $this->controllerResolver->getController($request);

        if (!\is_array($controller)) {
            return [];
        }

        $controllerReflection = new ReflectionClass(\get_class($controller[0]));
        $controllerMethodReflection = $controllerReflection->getMethod($controller[1]);

        return $this->annotationReader->getMethodAnnotations($controllerMethodReflection);
    }
}
