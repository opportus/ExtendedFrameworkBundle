<?php

namespace Opportus\ExtendedFrameworkBundle\DependencyInjection\Compiler;

use Opportus\ExtendedFrameworkBundle\Generator\DataGeneratorInterface;
use Opportus\ExtendedFrameworkBundle\Generator\ResponseGeneratorInterface;
use Opportus\ExtendedFrameworkBundle\Generator\ViewGeneratorInterface;
use Opportus\ExtendedFrameworkBundle\Generator\FlashGeneratorInterface;
use Opportus\ExtendedFrameworkBundle\Generator\ValueGeneratorInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * The generator strategies pass.
 *
 * @package Opportus\ExtendedFrameworkBundle\DependencyInjection\Compiler
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class GeneratorStrategiesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $generatorStrategyTags = [
            'opportus_extended_framework.data_generator'     => 'data_generator_strategy',
            'opportus_extended_framework.response_generator' => 'response_generator_strategy',
            'opportus_extended_framework.view_generator'     => 'view_generator_strategy',
            'opportus_extended_framework.flash_generator'    => 'flash_generator_strategy',
            'opportus_extended_framework.value_generator'    => 'value_generator_strategy',
        ];

        foreach ($generatorStrategyTags as $generatorId => $generatorStrategyTag) {
            $generatorStrategyPriorities = [];
            foreach ($container->findTaggedServiceIds($generatorStrategyTag) as $generatorStrategyId => $generatorStrategyTags) {
                $priority = $generatorStrategyTags[0]['priority'];
                $generatorStrategyPriorities[$generatorStrategyId] = $priority;
            }

            \arsort($generatorStrategyPriorities, \SORT_NUMERIC);

            $generatorStrategies = [];
            foreach ($generatorStrategyPriorities as $generatorStrategyId => $generatorStrategyPriority) {
                $generatorStrategies[] = new Reference($generatorStrategyId);
            }

            $container->getDefinition($generatorId)->setArgument(0, $generatorStrategies);
        }
    }
}
