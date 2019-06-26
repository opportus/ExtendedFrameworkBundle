<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\Generator;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractDataConfiguration;
use Symfony\Component\HttpFoundation\Request;

/**
 * The data generator interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface DataGeneratorInterface
{
    /**
     * Generates the data.
     *
     * @param AbstractDataConfiguration $dataConfiguration
     * @param Request $request
     * @return object
     * @throws GeneratorException
     */
    public function generate(AbstractDataConfiguration $dataConfiguration, Request $request): object;
}
