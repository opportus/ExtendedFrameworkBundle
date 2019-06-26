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

use Opportus\ExtendedFrameworkBundle\Annotation\ControllerAnnotationReaderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * The annotation reader subscriber.
 *
 * @package Opportus\ExtendedFrameworkBundle\EventSubscriber
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class AnnotationReaderSubscriber implements EventSubscriberInterface
{
    /**
     * @var ControllerAnnotationReaderInterface $annotationReader
     */
    private $annotationReader;

    /**
     * Constructs the controller annotation reader listener.
     * 
     * @param ControllerAnnotationReaderInterface $annotationReader
     */
    public function __construct(ControllerAnnotationReaderInterface $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => [
                ['readOnKernelController', 50],
            ],
        ];
    }

    /**
     * Reads annotations on the kernel controller.
     * 
     * @param FilterControllerEvent $event
     */
    public function readOnKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        $methodAnnotations = $this->annotationReader->getControllerAnnotations($request);

        $configurations = [];

        foreach ($methodAnnotations as $alias => $annotations) {
            $configuration = (array)$request->attributes->get('_extended_framework_configurations')[$alias] ?? [];
            $configuration = \array_merge($configuration, $annotations);

            $configurations[$alias] = $configuration;
        }

        $request->attributes->set('_extended_framework_configurations', $configurations);
    }
}
