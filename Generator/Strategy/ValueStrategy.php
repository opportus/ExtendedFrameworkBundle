<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Opportus\ExtendedFrameworkBundle\DataFetcher\DataFetcherInterface;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\Value as ValueConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractValueConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The value strategy.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Strategy
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class ValueStrategy implements ValueStrategyInterface
{
    /**
     * @var Opportus\ExtendedFrameworkBundle\DataFetcher\DataFetcherInterface $dataFetcher
     */
    private $dataFetcher;

    /**
     * Constructs the value strategy.
     *
     * @param Opportus\ExtendedFrameworkBundle\DataFetcher\DataFetcherInterface $dataFetcher
     */
    public function __construct(DataFetcherInterface $dataFetcher)
    {
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

        $value = $valueConfiguration->getValue();
        $parameters = $valueConfiguration->getParameters();

        foreach ($parameters as $key => $value) {
            $parameters[$key] = \strval($this->dataFetcher->fetch($value, $controllerResult->getData()));
        }

        return \str_replace(\array_keys($parameters), \array_values($parameters), $value);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(AbstractValueConfiguration $valueConfiguration, ControllerResultInterface $controllerResult, Request $request): bool
    {
        return $valueConfiguration instanceof ValueConfiguration && (null === $valueConfiguration->getStrategyFqcn() || self::class === $valueConfiguration->getStrategyFqcn());
    }
}
