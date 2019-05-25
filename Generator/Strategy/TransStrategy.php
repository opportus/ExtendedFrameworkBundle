<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Opportus\ExtendedFrameworkBundle\DataFetcher\DataFetcherInterface;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\Trans as TransConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractValueConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * The trans strategy.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Strategy
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class TransStrategy implements ValueStrategyInterface
{
    /**
     * @var Symfony\Component\Translation\TranslatorInterface $translator
     */
    private $translator;

    /**
     * @var Opportus\ExtendedFrameworkBundle\DataFetcher\DataFetcherInterface $dataFetcher
     */
    private $dataFetcher;

    /**
     * Constructs the trans strategy.
     *
     * @param Symfony\Component\Translation\TranslatorInterface $translator
     * @param Opportus\ExtendedFrameworkBundle\DataFetcher\DataFetcherInterface $dataFetcher
     */
    public function __construct(TranslatorInterface $translator, DataFetcherInterface $dataFetcher)
    {
        $this->translator = $translator;
        $this->dataFetcher = $dataFetcher;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(AbstractValueConfiguration $valueConfiguration, ControllerResultInterface $controllerResult, Request $request): string
    {
        if (false === $this->supports($valueConfiguration, $request)) {
            throw new GeneratorException(\sprintf(
                '"%s" does not support the value configuration within the current context.',
                self::class
            ));
        }

        $id = $valueConfiguration->getId();
        $parameters = $valueConfiguration->getParameters();
        $domain = $valueConfiguration->getDomain();
        $locale = $valueConfiguration->getLocale();

        foreach ($parameters as $key => $value) {
            $parameters[$key] = \strval($this->dataFetcher->fetch($value, $controllerResult->getData()));
        }

        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(AbstractValueConfiguration $valueConfiguration, ControllerResultInterface $controllerResult, Request $request): bool
    {
        return $valueConfiguration instanceof TransConfiguration && (null === $valueConfiguration->getStrategyFqcn() || self::class === $valueConfiguration->getStrategyFqcn());
    }
}
