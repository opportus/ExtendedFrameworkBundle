<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractViewConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\Template as TemplateConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Opportus\ExtendedFrameworkBundle\TemplateEngine\TemplateEngineInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The template strategy.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Strategy
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class TemplateStrategy implements ViewStrategyInterface
{
    /**
     * @var TemplateEngineInterface $templateEngine
     */
    private $templateEngine;

    /**
     * Constructs the Twig view strategy.
     *
     * @param TemplateEngineInterface $templateEngine
     */
    public function __construct(TemplateEngineInterface $templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(AbstractViewConfiguration $viewConfiguration, ControllerResultInterface $controllerResult, Request $request): string
    {
        if (false === $this->supports($viewConfiguration, $controllerResult, $request)) {
            throw new GeneratorException(\sprintf(
                '"%s" does not support the view configuration within the current context.',
                self::class
            ));
        }

        $data = $controllerResult->getData();
        $template = $viewConfiguration->getTemplateName();
        $context = null === $data ? [] : ['data' => $data];

        return $this->templateEngine->render($template, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(AbstractViewConfiguration $viewConfiguration, ControllerResultInterface $controllerResult, Request $request): bool
    {
        if (!$viewConfiguration instanceof TemplateConfiguration) {
            return false;
        }

        if (null !== $viewConfiguration->getStrategyFqcn() && self::class !== $viewConfiguration->getStrategyFqcn()) {
            return false;
        }

        return \in_array($viewConfiguration->getFormat(), ['text/html', 'text/xml']);
    }
}
