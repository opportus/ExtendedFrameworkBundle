<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractResponseConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\Response as ResponseConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Opportus\ExtendedFrameworkBundle\Generator\ValueGeneratorInterface;
use Opportus\ExtendedFrameworkBundle\Generator\ViewGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The response strategy.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Strategy
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class ResponseStrategy implements ResponseStrategyInterface
{
    /**
     * @var ViewGeneratorInterface $viewGenerator
     */
    private $viewGenerator;

    /**
     * @var ValueGeneratorInterface $valueGenerator
     */
    private $valueGenerator;

    /**
     * Constructs the response strategy.
     *
     * @param ViewGeneratorInterface $viewGenerator
     * @param ValueGeneratorInterface $valueGenerator
     */
    public function __construct(ViewGeneratorInterface $viewGenerator, ValueGeneratorInterface $valueGenerator)
    {
        $this->viewGenerator = $viewGenerator;
        $this->valueGenerator = $valueGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(AbstractResponseConfiguration $responseConfiguration, ControllerResultInterface $controllerResult, Request $request): Response
    {
        if (false === $this->supports($responseConfiguration, $controllerResult, $request)) {
            throw new GeneratorException(\sprintf(
                '"%s" does not support the response configuration within the current context.',
                self::class
            ));
        }

        // Defines the response content...
        $viewConfiguration = $responseConfiguration->getContent();
        $content = $this->viewGenerator->generate($viewConfiguration, $controllerResult, $request);

        // Defines headers...
        $headers = $responseConfiguration->getHeaders();
        foreach ($headers as $name => $value) {
            if (\is_object($value)) {
                $value = $this->valueGenerator->generate($value, $controllerResult, $request);
            }

            $headers[$name] = $value;
        }

        $headers['Content-Type'] = $responseConfiguration->getContent()->getFormat();

        // Defines status code...
        $statusCode = $controllerResult->getStatusCode();

        // Prepares the response...
        $response = new Response($content, $statusCode, $headers);
        $response->prepare($request);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(AbstractResponseConfiguration $responseConfiguration, ControllerResultInterface $controllerResult, Request $request): bool
    {
        if (!$responseConfiguration instanceof ResponseConfiguration) {
            return false;
        }

        if (null !== $responseConfiguration->getStrategyFqcn() && self::class !== $responseConfiguration->getStrategyFqcn()) {
            return false;
        }

        return true;
    }
}
