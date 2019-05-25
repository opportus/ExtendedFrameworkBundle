<?php

namespace Opportus\ExtendedFrameworkBundle\DataFetcher;

use Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor\AccessorInterface;

/**
 * The data fetcher interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\DataFetcher
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface DataFetcherInterface
{
    /**
     * Fetches a datum from data.
     *
     * @param Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor\GetterAccessor|Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor\PropertyAccessor|Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor\KeyAccessor $accessor
     * @param object|array $data
     * @throws Opportus\ExtendedFrameworkBundle\DataFetcher\DataFetcherException
     */
    public function fetch(AccessorInterface $accessor, $data);
}
