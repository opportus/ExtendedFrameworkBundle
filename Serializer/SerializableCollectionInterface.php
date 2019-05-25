<?php

namespace Opportus\ExtendedFrameworkBundle\Serializer;

/**
 * The serializable collection interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\Serializer
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE MIT
 */
interface SerializableCollectionInterface extends \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * Returns the serializable collection as array.
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Checks whether the serializable collection is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool;
}
