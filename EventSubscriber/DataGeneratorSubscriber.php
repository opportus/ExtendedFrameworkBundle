<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\EventSubscriber;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractDataConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\DataGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

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
     * @var DataGeneratorInterface $dataGenerator
     */
    private $dataGenerator;

    /**
     * Constructs the data generator subscriber.
     *
     * @param DataGeneratorInterface $dataGenerator
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
     * @param FilterControllerEvent $event
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
