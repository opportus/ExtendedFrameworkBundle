<?php

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
