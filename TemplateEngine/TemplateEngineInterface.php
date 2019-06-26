<?php

namespace Opportus\ExtendedFrameworkBundle\TemplateEngine;

/**
 * The template engine interface.
 *
 * @package Opportus\ExtendedFrameworkBundle\TemplateEngine
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
interface TemplateEngineInterface
{
    /**
     * Renders the template.
     * 
     * @param string $template
     * @param array $context
     * @throws TemplateEngineException
     */
    public function render(string $template, array $context = []): string;
}
