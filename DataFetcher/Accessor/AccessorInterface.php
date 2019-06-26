<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor;

/**
 * The accessor interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface AccessorInterface
{
    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName(): string;
}
