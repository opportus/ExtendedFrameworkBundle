<?php

namespace Opportus\ExtendedFrameworkBundle\EventSubscriber;

use Opportus\ExtendedFrameworkBundle\Generator\DataGeneratorInterface;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractDataConfiguration;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * The data generator subscriber.
 *
 * @package Opportus\ExtendedFrameworkBundle\EventSubscriber
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class DataGeneratorSubscriber implements EventSubscriberInterface
{
    /**
     * @var Opportus\ExtendedFrameworkBundle\Generator\DataGeneratorInterface $dataGenerator
     */
    private $dataGenerator;

    /**
     * Constructs the data generator subscriber.
     *
     * @param Opportus\ExtendedFrameworkBundle\Generator\DataGeneratorInterface $dataGenerator
     */
    public function __construct(DataGeneratorInterface $dataGenerator)
    {
        $this->dataGenerator = $dataGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => [
                ['generateOnKernelController', 0],
            ],
        ];
    }

    /**
     * Generates data on the kernel controller.
     *
     * @param Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     */
    public function generateOnKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        $dataConfigurations = $request->attributes->get('_extended_framework_configurations')['data'] ?? [];

        if (empty($dataConfigurations)) {
            return;
        }

        foreach ($dataConfigurations as $candidateDataConfiguration) {
            if ($candidateDataConfiguration instanceof AbstractDataConfiguration) {
                $dataConfiguration = $candidateDataConfiguration;
                break;
            }
        }

        if (!isset($dataConfiguration)) {
            return;
        }

        $request->attributes->set('data', $this->dataGenerator->generate($dataConfiguration, $request));
    }
}
