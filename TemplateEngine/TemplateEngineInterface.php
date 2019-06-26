<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
