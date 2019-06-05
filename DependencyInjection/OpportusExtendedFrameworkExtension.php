<?php

namespace Opportus\ExtendedFrameworkBundle\DependencyInjection;

use Opportus\ExtendedFrameworkBundle\DataFetcher\DataFetcherInterface;
use Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayInterface;
use Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface;
use Opportus\ExtendedFrameworkBundle\TemplateEngine\TemplateEngineInterface;
use Opportus\ExtendedFrameworkBundle\Generator\DataGeneratorInterface;
use Opportus\ExtendedFrameworkBundle\Generator\ResponseGeneratorInterface;
use Opportus\ExtendedFrameworkBundle\Generator\ViewGeneratorInterface;
use Opportus\ExtendedFrameworkBundle\Generator\FlashGeneratorInterface;
use Opportus\ExtendedFrameworkBundle\Generator\ValueGeneratorInterface;
use Opportus\ExtendedFrameworkBundle\Generator\Strategy\DataStrategyInterface;
use Opportus\ExtendedFrameworkBundle\Generator\Strategy\ResponseStrategyInterface;
use Opportus\ExtendedFrameworkBundle\Generator\Strategy\ViewStrategyInterface;
use Opportus\ExtendedFrameworkBundle\Generator\Strategy\FlashStrategyInterface;
use Opportus\ExtendedFrameworkBundle\Generator\Strategy\ValueSrategyInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Translation\Translator;
use Twig\Environment as Twig;
use Doctrine\ORM\EntityManager;

/**
 * The extension.
 *
 * @package Opportus\ExtendedFrameworkBundle\DependencyInjection
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE MIT
 */
final class OpportusExtendedFrameworkExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $this->registerDataFetcherServices($configs, $container, $loader);
        $this->registerTemplateEngineServices($configs, $container, $loader);
        $this->registerEntityGatewayServices($configs, $container, $loader);
        $this->registerGeneratorServices($configs, $container, $loader);
        $this->registerSubscriberServices($configs, $container, $loader);
        $this->registerValidatorServices($configs, $container, $loader);

        $this->autoTag($container);
    }

    /**
     * Registers the data fetcher services.
     *
     * @param array $configs
     * @param Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param Symfony\Component\DependencyInjection\Loader\XmlFileLoader $loader
     * @throws Opportus\ExtendedFrameworkBundle\DependencyInjection\DependencyInjectionException
     */
    private function registerDataFetcherServices(array $configs, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('data_fetcher.xml');

        $container->setAlias(DataFetcherInterface::class, 'opportus_extended_framework.data_fetcher');
    }

    /**
     * Registers the template engine services.
     *
     * @param array $configs
     * @param Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param Symfony\Component\DependencyInjection\Loader\XmlFileLoader $loader
     * @throws Opportus\ExtendedFrameworkBundle\DependencyInjection\DependencyInjectionException
     */
    private function registerTemplateEngineServices(array $configs, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('template_engine.xml');

        if ($container->has('opportus_extended_framework.template_engine')) {
            $userTemplateEngineFqcn = $container->getParameterBag()->resolveValue(
                $container->findDefinition('opportus_extended_framework.template_engine')->getClass()
            );

            if (!\is_subclass_of($userTemplateEngineFqcn, TemplateEngineInterface::class)) {
                throw new DependencyInjectionException(sprintf(
                    '"%s" must implement "%s", got an instance of "%s".',
                    'opportus_extended_framework.template_engine',
                    TemplateEngineInterface::class,
                    $userTemplateEngineFqcn
                ));
            }
        } else {
            if (\class_exists(Twig::class)) {
                $container->setAlias('opportus_extended_framework.template_engine', 'opportus_extended_framework.twig_template_engine');
            } else {
                $container->removeDefinition('opportus_extended_framework.twig_template_engine');
                $container->setAlias('opportus_extended_framework.template_engine', 'opportus_extended_framework.default_template_engine');
            }
        }

        $container->setAlias(TemplateEngineInterface::class, 'opportus_extended_framework.template_engine');
    }

    /**
     * Registers the entity gateway services.
     *
     * @param array $configs
     * @param Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param Symfony\Component\DependencyInjection\Loader\XmlFileLoader $loader
     * @throws Opportus\ExtendedFrameworkBundle\DependencyInjection\DependencyInjectionException
     */
    private function registerEntityGatewayServices(array $configs, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('entity_gateway.xml');

        $container->setAlias(QueryBuilderInterface::class, 'opportus_extended_framework.query_builder');

        if ($container->has('opportus_extended_framework.entity_gateway')) {
            $userEntityGatewayFqcn = $container->getParameterBag()->resolveValue(
                $container->findDefinition('opportus_extended_framework.entity_gateway')->getClass()
            );

            if (!\is_subclass_of($userEntityGatewayFqcn, EntityGatewayInterface::class)) {
                throw new DependencyInjectionException(sprintf(
                    '"%s" must implement "%s", got an instance of "%s".',
                    'opportus_extended_framework.entity_gateway',
                    EntityGatewayInterface::class,
                    $userEntityGatewayFqcn
                ));
            }
        } else {
            if (!\class_exists(EntityManager::class)) {
                throw new DependencyInjectionException(\sprintf(
                    'In order to be operational, the extended framework either needs Doctrine ORM installed or a service implementing "%s" with ID "%s".',
                    EntityGatewayInterface::class,
                    'opportus_extended_framework.entity_gateway'
                ));
            }

            $container->setAlias('opportus_extended_framework.entity_gateway', 'opportus_extended_framework.doctrine_entity_gateway');
        }

        $container->setAlias(EntityGatewayInterface::class, 'opportus_extended_framework.entity_gateway');
    }

    /**
     * Registers the generator services.
     *
     * @param array $configs
     * @param Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param Symfony\Component\DependencyInjection\Loader\XmlFileLoader $loader
     * @throws Opportus\ExtendedFrameworkBundle\DependencyInjection\DependencyInjectionException
     */
    private function registerGeneratorServices(array $configs, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('generator.xml');

        if (!\class_exists(Translator::class)) {
            $container->removeDefinition('opportus_extended_framework.value_generator.trans_strategy');
        }

        $container->setAlias(DataGeneratorInterface::class, 'opportus_extended_framework.data_generator');
        $container->setAlias(ResponseGeneratorInterface::class, 'opportus_extended_framework.response_generator');
        $container->setAlias(ViewGeneratorInterface::class, 'opportus_extended_framework.view_generator');
        $container->setAlias(FlashGeneratorInterface::class, 'opportus_extended_framework.flash_generator');
        $container->setAlias(ValueGeneratorInterface::class, 'opportus_extended_framework.value_generator');
    }

    /**
     * Registers the subscriber services.
     *
     * @param array $configs
     * @param Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param Symfony\Component\DependencyInjection\Loader\XmlFileLoader $loader
     * @throws Opportus\ExtendedFrameworkBundle\DependencyInjection\DependencyInjectionException
     */
    private function registerSubscriberServices(array $configs, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('subscriber.xml');
    }

    /**
     * Registers the validator services.
     *
     * @param array $configs
     * @param Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param Symfony\Component\DependencyInjection\Loader\XmlFileLoader $loader
     * @throws Opportus\ExtendedFrameworkBundle\DependencyInjection\DependencyInjectionException
     */
    private function registerValidatorServices(array $configs, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('validator.xml');
    }

    /**
     * Auto tags.
     *
     * @param Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function autoTag(ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(DataStrategyInterface::class)->addTag('opportus_extended_framework.data_generator_strategy');
        $container->registerForAutoconfiguration(ResponseStrategyInterface::class)->addTag('opportus_extended_framework.response_generator_strategy');
        $container->registerForAutoconfiguration(ViewStrategyInterface::class)->addTag('opportus_extended_framework.view_generator_strategy');
        $container->registerForAutoconfiguration(FlashStrategyInterface::class)->addTag('opportus_extended_framework.flash_generator_strategy');
        $container->registerForAutoconfiguration(ValueStrategyInterface::class)->addTag('opportus_extended_framework.value_generator_strategy');
    }
}
