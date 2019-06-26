<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractFlashConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\Flash as FlashConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Opportus\ExtendedFrameworkBundle\Generator\ValueGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * The flash strategy.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Strategy
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class FlashStrategy implements FlashStrategyInterface
{
    /**
     * @var SessionInterface $session
     */
    private $session;

    /**
     * @var ValueGeneratorInterface $valueGenerator
     */
    private $valueGenerator;

    /**
     * Constructs the flash strategy.
     *
     * @param SessionInterface $session
     * @param ValueGeneratorInterface $valueGenerator
     */
    public function __construct(SessionInterface $session, ValueGeneratorInterface $valueGenerator)
    {
        $this->session = $session;
        $this->valueGenerator = $valueGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(AbstractFlashConfiguration $flashConfiguration, ControllerResultInterface $controllerResult, Request $request)
    {
        if (false === $this->supports($flashConfiguration, $controllerResult, $request)) {
            throw new GeneratorException(\sprintf(
                '"%s" does not support the flash configuration within the current context.',
                self::class
            ));
        }

        $statusCode = $flashConfiguration->getStatusCode();
        $message = $this->valueGenerator->generate($flashConfiguration->getMessage(), $controllerResult);

        $this->session->getFlashBag()->add($statusCode, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(AbstractFlashConfiguration $flashConfiguration, ControllerResultInterface $controllerResult, Request $request): bool
    {
        if (!$flashConfiguration instanceof FlashConfiguration) {
            return false;
        }

        if (null !== $flashConfiguration->getStrategyFqcn() && self::class !== $flashConfiguration->getStrategyFqcn()) {
            return false;
        }

        return true;
    }
}
