<?php

namespace Opportus\ExtendedFrameworkBundle\Serializer;

/**
 * The serializable collection.
 *
 * @package Opportus\ExtendedFrameworkBundle\Serializer
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE MIT
 */
final class SerializableCollection implements SerializableCollectionInterface
{
    /**
     * @var object[] $items
     */
    private $items = [];

    /**
     * Constructs the serializable collection
     *
     * @param object[] $items
     * @throws Opportus\ExtendedFrameworkBundle\Serializer\SerializerException
     */
    public function __construct($items)
    {
        if (!\is_array($items)) {
            throw new SerializerException(
                \sprintf(
                    'Invalid argument: expecting "items" to be of type "array", got "%s" type.',
                    \gettype($items)
                )
            );
        }

        foreach ($items as $item) {
            if (!\is_object($item)) {
                throw new SerializerException(
                    \sprintf(
                        'Invalid argument: expecting "items" to be an array with 0 or more elements of type "object", got an element of type "%s".',
                        \gettype($item)
                    )
                );
            }

            $this->items[] = $item;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return \count($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * {@inheritDoc}
     *
     * @throws Opportus\ExtendedFrameworkBundle\Serializer\SerializerException
     */
    public function offsetSet($offset, $value)
    {
        throw new SerializerException(
            \sprintf(
                'Invalid "%s" operation: attempting to set an element of an immutable array.',
                __METHOD__
            )
        );
    }

    /**
     * {@inheritDoc}
     *
     * @throws Opportus\ExtendedFrameworkBundle\Serializer\SerializerException
     */
    public function offsetUnset($offset)
    {
        throw new SerializerException(
            \sprintf(
                'Invalid "%s" operation: attempting to unset an element of an immutable array.',
                __METHOD__
            )
        );
    }
}
