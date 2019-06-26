<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\DataFetcher\DataFetcherInterface;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractValueConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\Route as RouteConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * The route strategy.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Strategy
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class RouteStrategy implements ValueStrategyInterface
{
    /**
     * @var RouterInterface $router
     */
    private $router;

    /**
     * @var DataFetcherInterface $dataFetcher
     */
    private $dataFetcher;

    /**
     * Constructs the route strategy.
     *
     * @param RouterInterface $router
     * @param DataFetcherInterface $dataFetcher
     */
    public function __construct(RouterInterface $router, DataFetcherInterface $dataFetcher)
    {
        $this->router = $router;
        $this->dataFetcher = $dataFetcher;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(AbstractValueConfiguration $valueConfiguration, ControllerResultInterface $controllerResult, Request $request): string
    {
        if (false === $this->supports($valueConfiguration, $controllerResult, $request)) {
            throw new GeneratorException(\sprintf(
                '"%s" does not support the value configuration within the current context.',
                self::class
            ));
        }

        $name = $valueConfiguration->getName();
        $parameters = $valueConfiguration->getParameters();

        foreach ($parameters as $key => $value) {
            $parameters[$key] = \strval($this->dataFetcher->fetch($value, $controllerResult->getData()));
        }

        return $this->router->generate($name, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(AbstractValueConfiguration $valueConfiguration, ControllerResultInterface $controllerResult, Request $request): bool
    {
        return $valueConfiguration instanceof RouteConfiguration && (null === $valueConfiguration->getStrategyFqcn() || self::class === $valueConfiguration->getStrategyFqcn());
    }
}
