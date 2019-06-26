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

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * The template engine.
 *
 * @package Opportus\ExtendedFrameworkBundle\TemplateEngine
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class TemplateEngine implements TemplateEngineInterface
{
    /**
     * @var KernelInterface $kernel
     */
    private $kernel;

    /**
     * Cosntructs the  template engine.
     * 
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * {@inheritdoc}
     */
    public function render(string $template, array $context = []): string
    {
        $template = \sprintf('%s%s%s', $this->kernel->getProjectDir(), \DIRECTORY_SEPARATOR, \trim($template, \DIRECTORY_SEPARATOR));

        if (!\file_exists($template)) {
            throw new TemplateEngineException(\sprintf(
                'Template "%s" does not exist.',
                $template
            ));
        }

        extract($context);

        ob_start();
        
        require_once($template);

		return ob_get_clean();
    }
}
