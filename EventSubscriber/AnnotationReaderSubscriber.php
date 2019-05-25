<?php

namespace Opportus\ExtendedFrameworkBundle\EventSubscriber;

use Opportus\ExtendedFrameworkBundle\Annotation\AnnotationInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Doctrine\Common\Annotations\Reader;

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
     * @var Doctrine\Common\Annotations\Reader $reader
     */
    private $reader;

    /**
     * Constructs the controller annotation reader listener.
     * 
     * @param Doctrine\Common\Annotations\Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
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
     * @param Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     */
    public function readOnKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!\is_array($controller)) {
            return;
        }

        $controllerClassName = \get_class($controller[0]);
        $controllerClassReflection = new \ReflectionClass($controllerClassName);
        $controllerMethodReflection = $controllerClassReflection->getMethod($controller[1]);

        $methodAnnotations = $this->reader->getMethodAnnotations($controllerMethodReflection);

        $supportedAnnotations = [];

        foreach ($methodAnnotations as $annotation) {
            if ($annotation instanceof AnnotationInterface) {
                $supportedAnnotations[$annotation->getAnnotationAlias()][] = $annotation;
            }
        }

        $request = $event->getRequest();
        $configurations = [];

        foreach ($supportedAnnotations as $alias => $annotations) {
            $configuration = (array)$request->attributes->get('_extended_framework_configurations')[$alias] ?? [];
            $configuration = \array_merge($configuration, $annotations);

            $configurations[$alias] = $configuration;
        }

        $request->attributes->set('_extended_framework_configurations', $configurations);
    }
}
