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

use Doctrine\Common\Annotations\Reader;
use Opportus\ExtendedFrameworkBundle\Annotation\AnnotationInterface;
use ReflectionMethod;

/**
 * The annotation reader.
 *
 * @package Opportus\ExtendedFrameworkBundle\Annotation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class AnnotationReader implements AnnotationReaderInterface
{
    /**
     * @var Reader $reader
     */
    private $reader;

    /**
     * Constructs the annotation reader.
     *
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodAnnotations(ReflectionMethod $methodReflection): array
    {
        $methodAnnotations = $this->reader->getMethodAnnotations($methodReflection);

        $annotations = [];

        foreach ($methodAnnotations as $annotation) {
            if ($annotation instanceof AnnotationInterface) {
                $annotations[$annotation->getAnnotationAlias()][] = $annotation;
            }
        }

        return $annotations;
    }
}
