<?php

namespace Opportus\ExtendedFrameworkBundle\EntityGateway\Query;

use Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayException;

/**
 * The query result.
 *
 * @package Opportus\ExtendedFrameworkBundle\EntityGateway\Query
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE MIT
 */
final class QueryResult implements QueryResultInterface
{
    /**
     * @var object[] $entities
     */
    private $entities = [];

    /**
     * Constructs the query result.
     *
     * @param object[] $entities
     * @throws EntityGatewayException If the argument is not of type array and if it contains something else than 0 or more objects
     */
    public function __construct($entities)
    {
        if (!\is_array($entities)) {
            throw new EntityGatewayException(\sprintf(
                'Invalid argument: expecting "entities" to be of type "array", got "%s" type.',
                \gettype($entities)
            ));
        }

        foreach ($entities as $entity) {
            if (!\is_object($entity)) {
                throw new EntityGatewayException(\sprintf(
                    'Invalid argument: expecting "entities" to be an array with 0 or more elements of type "object", got an element of type "%s".',
                    \gettype($entity)
                ));
            }

            $this->entities[] = $entity;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return $this->entities;
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty(): bool
    {
        return empty($this->entities);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->entities);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return \count($this->entities);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->entities[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->entities[$offset];
    }

    /**
     * {@inheritDoc}
     *
     * @throws EntityGatewayException
     */
    public function offsetSet($offset, $value)
    {
        throw new EntityGatewayException(\sprintf(
            'Invalid "%s" operation: attempting to set an element of an immutable array.',
            __METHOD__
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @throws EntityGatewayException
     */
    public function offsetUnset($offset)
    {
        throw new EntityGatewayException(\sprintf(
            'Invalid "%s" operation: attempting to unset an element of an immutable array.',
            __METHOD__
        ));
    }
}
