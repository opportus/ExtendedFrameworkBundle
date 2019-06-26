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

use Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor\GetterAccessor;
use Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor\KeyAccessor;
use Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor\PropertyAccessor;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;

/**
 * The trans configuration.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 *
 * @Annotation
 * @Target("ANNOTATION")
 */
final class Trans extends AbstractValueConfiguration
{
    /**
     * @var string $id
     */
    private $id;

    /**
     * @var array $parameters
     */
    private $parameters;

    /**
     * @var null|string $domain
     */
    private $domain;

    /**
     * @var null|string $locale
     */
    private $locale;

    /**
     * Constructs the trans configuration.
     *
     * @param array $values
     * @throws GeneratorException
     */
    public function __construct(array $values = [])
    {
        $this->id = $values['id'] ?? null;
        $this->parameters = $values['parameters'] ?? [];
        $this->domain = $values['domain'] ?? null;
        $this->locale = $values['locale'] ?? null;
        $options = $values['options'] ?? [];
        $strategyFqcn = $values['strategyFqcn'] ?? null;

        parent::__construct([
            'options' => $options,
            'strategyFqcn' => $strategyFqcn,
        ]);

        if (!\is_string($this->id)) {
            throw new GeneratorException(\sprintf(
                '"id" is expected to be a "string", got a "%s".',
                \gettype($this->id)
            ));
        }

        foreach ($this->parameters as $parameterKey => $parameterValue) {
            if (!\is_object($parameterValue)) {
                throw new GeneratorException(\sprintf(
                    'Parameter "%s" is expected to be an "object", got a "%s".',
                    $parameterKey,
                    \gettype($parameterValue)
                ));
            }

            if (!($parameterValue instanceof GetterAccessor || $parameterValue instanceof PropertyAccessor || $parameterValue instanceof KeyAccessor)) {
                throw new GeneratorException(\sprintf(
                    'Parameter "%s" is expected to be an instance of either "%s" or "%s" or "%s", got an instance of type "%s".',
                    $parameterKey,
                    GetterAccessor::class,
                    PropertyAccessor::class,
                    KeyAccessor::class,
                    \get_class($parameterValue)
                ));
            }
        }

        if (null !== $this->domain) {
            if (!\is_string($this->domain)) {
                throw new GeneratorException(\sprintf(
                    '"domain" is expected to be a "string", got a "%s".',
                    \gettype($this->domain)
                ));
            }
        }

        if (null !== $this->locale) {
            if (!\is_string($this->locale)) {
                throw new GeneratorException(\sprintf(
                    '"locale" is expected to be a "string", got a "%s".',
                    \gettype($this->locale)
                ));
            }
        }
    }

    /**
     * Gets the id.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Gets the parameters.
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Gets the domain.
     *
     * @return null|string
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * Gets the locale.
     *
     * @return null|string
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }
}
