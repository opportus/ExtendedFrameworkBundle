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

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractFlashConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerException;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResult;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Opportus\ExtendedFrameworkBundle\Generator\FlashGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * The flash generator subscriber.
 *
 * @package Opportus\ExtendedFrameworkBundle\EventSubscriber
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class FlashGeneratorSubscriber implements EventSubscriberInterface
{
    /**
     * @var FlashGeneratorInterface $flashGenerator
     */
    private $flashGenerator;

    /**
     * Constructs the flash generator subscriber.
     *
     * @param FlashGeneratorInterface $flashGenerator
     */
    public function __construct(FlashGeneratorInterface $flashGenerator)
    {
        $this->flashGenerator = $flashGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                ['generateOnKernelView', 0],
            ],
            KernelEvents::EXCEPTION => [
                ['generateOnKernelException', 0],
            ],
        ];
    }

    /**
     * Generates a flash on the kernel view.
     *
     * @param GetResponseForControllerResultEvent $event
     */
    public function generateOnKernelView(GetResponseForControllerResultEvent $event)
    {
        $controllerResult = $event->getControllerResult();

        if (!\is_object($controllerResult) || !$controllerResult instanceof ControllerResult) {
            return;
        }

        $request = $event->getRequest();
        $flashConfiguration = $this->getContextualFlashConfiguration($controllerResult, $request);

        if (null === $flashConfiguration) {
            return;
        }

        $this->flashGenerator->generate(
            $flashConfiguration,
            $controllerResult,
            $request
        );
    }

    /**
     * Generates a flash on the kernel exception.
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function generateOnKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if (!$exception instanceof ControllerException) {
            return;
        }

        $request = $event->getRequest();
        $flashConfiguration = $this->getContextualFlashConfiguration($controllerResult, $request);

        if (null === $flashConfiguration) {
            return;
        }

        $this->flashGenerator->generate(
            $flashConfiguration,
            $controllerResult,
            $request
        );
    }

    /**
     * Gets the contextual flash configuration.
     * 
     * @param ControllerResultInterface $controllerResult
     * @param Request $request
     * @return null|AbstractFlashConfiguration
     */
    private function getContextualFlashConfiguration(ControllerResultInterface $controllerResult, Request $request): ?AbstractFlashConfiguration
    {
        $flashConfigurations = $request->attributes->get('_extended_framework_configurations')['flash'] ?? [];

        if (empty($flashConfigurations)) {
            return null;
        }

        foreach ($flashConfigurations as $flashConfiguration) {
            if ($flashConfiguration instanceof AbstractFlashConfiguration && $flashConfiguration->isInContext($controllerResult, $request)) {
                $contextualFlashConfiguration = $flashConfiguration;
                break;
            }
        }

        if (!isset($contextualFlashConfiguration)) {
            return null;
        }

        return $contextualFlashConfiguration;
    }
}
