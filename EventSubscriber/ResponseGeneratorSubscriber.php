<?php

namespace Opportus\ExtendedFrameworkBundle\EventSubscriber;

use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResult;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerException;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractResponseConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\ResponseGeneratorInterface;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * The response generator subscriber.
 *
 * @package Opportus\ExtendedFrameworkBundle\EventSubscriber
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class ResponseGeneratorSubscriber implements EventSubscriberInterface
{
    /**
     * @var Opportus\ExtendedFrameworkBundle\Generator\ResponseGeneratorInterface $responseGenerator
     */
    private $responseGenerator;

    /**
     * Constructs the response generator subscriber.
     *
     * @param Opportus\ExtendedFrameworkBundle\Generator\ResponseGeneratorInterface $responseGenerator
     */
    public function __construct(ResponseGeneratorInterface $responseGenerator)
    {
        $this->responseGenerator = $responseGenerator;
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
     * Generates a response on the kernel view.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     * @throws Opportus\ExtendedFrameworkBundle\Generator\GeneratorException
     */
    public function generateOnKernelView(GetResponseForControllerResultEvent $event)
    {
        $controllerResult = $event->getControllerResult();

        if (!\is_object($controllerResult) || !$controllerResult instanceof ControllerResult) {
            return;
        }

        $request = $event->getRequest();
        $responseConfiguration = $this->getContextualResponseConfiguration($controllerResult, $request);

        $event->setResponse($this->responseGenerator->generate(
            $responseConfiguration,
            $controllerResult,
            $request
        ));
    }

    /**
     * Generates a response on the kernel exception.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     * @throws Opportus\ExtendedFrameworkBundle\Generator\GeneratorException
     */
    public function generateOnKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if (!$exception instanceof ControllerException) {
            return;
        }

        $request = $event->getRequest();
        $responseConfiguration = $this->getContextualResponseConfiguration($exception, $request);

        $event->setResponse($this->responseGenerator->generate(
            $responseConfiguration,
            $exception,
            $request
        ));
    }

    /**
     * Gets the contextual response configuration.
     * 
     * @param Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface $controllerResult
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractResponseConfiguration
     * @throws Opportus\ExtendedFrameworkBundle\Generator\GeneratorException
     */
    private function getContextualResponseConfiguration(ControllerResultInterface $controllerResult, Request $request): AbstractResponseConfiguration
    {
        $responseConfigurations = $request->attributes->get('_extended_framework_configurations')['response'] ?? [];

        if (empty($responseConfigurations)) {
            throw new GeneratorException(\sprintf(
                'Response of action "%s" must be configured in order for the response generator to generate a response.',
                $request->attributes->get('_controller')
            ));
        }

        foreach ($responseConfigurations as $responseConfiguration) {
            if ($responseConfiguration instanceof AbstractResponseConfiguration && $responseConfiguration->isInContext($controllerResult, $request)) {
                $contextualResponseConfiguration = $responseConfiguration;
                break;
            }
        }

        if (!isset($contextualResponseConfiguration)) {
            throw new GeneratorException(\sprintf(
                'No response configuration found on action "%s" for the following context:%s
                    controller result status code: "%d"%s
                    request acceptable content types: "%s"
                ',
                $request->attributes->get('_controller'),
                \PHP_EOL,
                $controllerResult->getStatusCode(),
                \PHP_EOL,
                \implode(',', $request->getAcceptableContentTypes())
            ));
        }

        return $contextualResponseConfiguration;
    }
}
