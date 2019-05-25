<?php

namespace Opportus\ExtendedFrameworkBundle\Serializer\Normalizer;

use Opportus\ExtendedFrameworkBundle\Serializer\SerializableCollection;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

/**
 * The serializable collection normalizer.
 *
 * @package Opportus\ExtendedFrameworkBundle\Serializer\Normalizer
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class SerializableCollectionNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;
    
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        if (false === $this->supportsNormalization($object, $format)) {
            throw new InvalidArgumentException(\sprintf(
                'Object must be an object of type "%s".',
                SerializableCollection::class
            ));
        }

        $normalizedItems = [];
        foreach ($object as $collectionItem) {
            $normalizedItems[] = $this->normalizer->normalize($collectionItem);
        }

        return $normalizedItems;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return \is_object($data) && SerializableCollection::class === \get_class($data);
    }
}
