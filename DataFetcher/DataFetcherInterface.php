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
use Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor\GetterAcessor;
use Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor\KeyAccessor;
use Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor\PropertyAcessor;

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
     * @param GetterAccessor|PropertyAccessor|KeyAccessor $accessor
     * @param object|array $data
     * @throws DataFetcherException
     */
    public function fetch(AccessorInterface $accessor, $data);
}
