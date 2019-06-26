<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\DataFetcher;

use Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor\AccessorInterface;
use Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor\GetterAccessor;
use Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor\KeyAccessor;
use Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor\PropertyAccessor;

/**
 * The data fetcher.
 *
 * @package Opportus\ExtendedFrameworkBundle\DataFetcher
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class DataFetcher implements DataFetcherInterface
{
    /**
     * {@inheritdoc}
     */
    public function fetch(AccessorInterface $accessor, $data)
    {
        if (!\is_array($data) && !\is_object($data)) {
            throw new DataFetcherException(\sprintf(
                'Fetchable data must be either of type array or object. Got data of type "%s".',
                \gettype($data)
            ));
        }

        if ($accessor instanceof GetterAccessor) {
            if (!\is_callable([$data, $accessor->getName()])) {
                throw new DataFetcherException(\sprintf(
                    'Got an accessor of type "%s", but data neither is an object nor has a public method called "%s".',
                    GetterAccessor::class,
                    $accessor->getName()
                ));
            }

            $datum = $data->{$accessor->getName()}();
        } elseif ($accessor instanceof PropertyAccessor) {
            if (!\is_object($data) || !\property_exists($data, $accessor->getName()) || !\array_key_exists($accessor->getName(), \get_object_vars($data))) {
                throw new DataFetcherException(\sprintf(
                    'Got an accessor of type "%s", but data neither is an object nor has a public property called "%s".',
                    PropertyAccessor::class,
                    $accessor->getName()
                ));
            }

            $datum = $data->{$accessor->getName()};
        } elseif ($accessor instanceof KeyAccessor) {
            if (!\is_array($data) || !\array_key_exists($accessor->getName(), $data)) {
                throw new DataFetcherException(\sprintf(
                    'Got an accessor of type "%s", but data neither is an array nor has a key called "%s".',
                    KeyAccessor::class,
                    $accessor->getName()
                ));
            }

            $datum = $data[$accessor->getName()];
        } else {
            throw new DataFetcherException(\sprintf(
                '"%s" supports only accessors of type "%s" or "%s" or "%s". Got an object of type "%s".',
                self::class,
                GetterAccessor::class,
                PropertyAccessor::class,
                KeyAccessor::class,
                \get_class($accessor)
            ));
        }

        return $datum;
    }
}
