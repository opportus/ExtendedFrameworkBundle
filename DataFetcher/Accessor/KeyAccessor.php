<?php

namespace Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor;

use Opportus\ExtendedFrameworkBundle\Annotation\AnnotationInterface;

/**
 * The key accessor.
 *
 * @package Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 *
 * @Annotation
 * @Target("ANNOTATION")
 */
final class KeyAccessor implements AccessorInterface, AnnotationInterface
{
    use AccessorTrait;
}
