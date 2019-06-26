<?php

namespace Opportus\ExtendedFrameworkBundle\TemplateEngine;

use Twig\Environment;

/**
 * The Twig template engine.
 *
 * @package Opportus\ExtendedFrameworkBundle\TemplateEngine
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class TwigTemplateEngine implements TemplateEngineInterface
{
    /**
     * @var Environment $twig
     */
    private $twig;

    /**
     * Constructs the Twig template engine.
     *
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function render(string $template, array $context = []): string
    {
        try {
            return $this->twig->render($template, $context);
        } catch (\Exception $exception) {
            throw new TemplateEngineException($exception->getMessage(), 0, $exception);
        }
    }
}
