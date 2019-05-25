<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Configuration;

use Opportus\ExtendedFrameworkBundle\Annotation\AnnotationInterface;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The abstract flash configuration.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
abstract class AbstractFlashConfiguration implements ContextualConfigurationInterface, AnnotationInterface
{
    use ConfigurationTrait;

    /**
     * @var int $statusCode
     */
    private $statusCode;

    /**
     * @var Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractValueConfiguration $message
     */
    private $message;

    /**
     * Constructs the flash configuration.
     *
     * @param array $values
     * @throws Opportus\ExtendedFrameworkBundle\Generator\GeneratorException
     */
    public function __construct(array $values = [])
    {
        $this->statusCode = $values['statusCode'] ?? null;
        $this->message = $values['message'] ?? null;
        $this->options = $values['options'] ?? [];
        $this->strategyFqcn = $values['strategyFqcn'] ?? null;

        if (!\is_int($this->statusCode)) {
            throw new GeneratorException(\sprintf(
                '"statusCode" is expected to be an "integer", got a "%s".',
                \gettype($this->statusCode)
            ));
        }

        if (!\is_object($this->messsage)) {
            throw new GeneratorException(\sprintf(
                '"message" is expected to be an "object", got a "%s".',
                \gettype($this->message)
            ));
        }

        if (!$this->message instanceof AbstractValueConfiguration) {
            throw new GeneratorException(\sprintf(
                '"message" is expected to be an instance of "%s", got an instance of type "%s".',
                AbstractValueConfiguration::class,
                \get_class($this->message)
            ));
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
        return 'flash';
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
     * Gets the message.
     *
     * @return Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractValueConfiguration $message
     */
    public function getMessage(): AbstractValueConfiguration
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function isInContext(ControllerResultInterface $controllerResult, Request $request): bool
    {
        return $controllerResult->getStatusCode() === $this->getStatusCode();
    }
}
