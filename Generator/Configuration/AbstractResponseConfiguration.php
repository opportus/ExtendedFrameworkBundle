<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\Generator\Configuration;

use Opportus\ExtendedFrameworkBundle\Annotation\AnnotationInterface;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Symfony\Component\HttpFoundation\Request;

/**
 * The abstract response configuration.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
abstract class AbstractResponseConfiguration implements ContextualConfigurationInterface, AnnotationInterface
{
    use ConfigurationTrait;

    /**
     * @var int $statusCode
     */
    private $statusCode;

    /**
     * @var AbstractViewConfiguration $content
     */
    private $content;

    /**
     * @var array $headers
     */
    private $headers;

    /**
     * Constructs the response configuration.
     *
     * @param array $values
     * @throws GeneratorException
     */
    public function __construct(array $values = [])
    {
        $this->statusCode = $values['statusCode'] ?? null;
        $this->content = $values['content'] ?? null;
        $this->headers = $values['headers'] ?? [];
        $this->options = $values['options'] ?? [];
        $this->strategyFqcn = $values['strategyFqcn'] ?? null;

        if (!\is_int($this->statusCode)) {
            throw new GeneratorException(\sprintf(
                '"statusCode" is expected to be an "integer", got a "%s".',
                \gettype($this->statusCode)
            ));
        }

        if (!\is_object($this->content)) {
            throw new GeneratorException(\sprintf(
                '"content" is expected to be an "object", got a "%s".',
                \gettype($this->content)
            ));
        }

        if (!$this->content instanceof AbstractViewConfiguration) {
            throw new GeneratorException(\sprintf(
                '"content" is expected to be an instance of "%s", got an instance of type "%s".',
                AbstractViewConfiguration::class,
                \get_class($this->content)
            ));
        }

        foreach ($this->headers as $headerName => $headerValue) {
            if (!\is_object($headerValue)) {
                throw new GeneratorException(\sprintf(
                    '"header" "%s" is expected to be an "object", got a "%s".',
                    $headerName,
                    \gettype($headerValue)
                ));
            }

            if (!$headerValue instanceof AbstractValueConfiguration) {
                throw new GeneratorException(\sprintf(
                    '"header" "%s" is expected to be an instance of "%s", got an instance of type "%s".',
                    $headerName,
                    AbstractValueConfiguration::class,
                    \get_class($headerValue)
                ));
            }
        }

        if (!\is_array($this->options)) {
            throw new GeneratorException(\sprintf(
                '"options" is expected to be an array, got a "%s".',
                \gettype($this->options)
            ));
        }

        if (null !== $this->strategyFqcn) {
            if (!\is_string($this->strategyFqcn)) {
                throw new GeneratorException(\sprintf(
                    '"strategyFqcn" is expected to be a "string", got a "%s".',
                    \gettype($this->strategyFqcn)
                ));
            }

            if (!\class_exists($this->strategyFqcn)) {
                throw new GeneratorException(\sprintf(
                    '"strategyFqcn" is expected to be a Fully Qualified Class Name, got class "%s" which does not exist.',
                    $this->strategyFqcn
                ));
            }
        }
    }

    /**
     * Gets the annotation alias.
     *
     * @return string
     */
    public function getAnnotationAlias(): string
    {
        return 'response';
    }

    /**
     * Gets the status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Gets the content.
     *
     * @return AbstractViewConfiguration
     */
    public function getContent(): AbstractViewConfiguration
    {
        return $this->content;
    }

    /**
     * Gets the headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * {@inheritdoc}
     */
    public function isInContext(ControllerResultInterface $controllerResult, Request $request): bool
    {
        return $controllerResult->getStatusCode() === $this->getStatusCode() && $this->content->isInContext($controllerResult, $request);
    }
}
