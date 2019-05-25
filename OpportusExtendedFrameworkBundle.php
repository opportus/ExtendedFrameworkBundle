<?php

namespace Opportus\ExtendedFrameworkBundle;

use Opportus\ExtendedFrameworkBundle\DependencyInjection\Compiler\GeneratorStrategiesPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * The extended framework bundle.
 *
 * @package Opportus\ExtendedFrameworkBundle
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE MIT
 */
class OpportusExtendedFrameworkBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new GeneratorStrategiesPass());
    }
}
