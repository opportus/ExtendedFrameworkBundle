<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\Generator;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractResponseConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The response generator.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class ResponseGenerator implements ResponseGeneratorInterface
{
    use GeneratorTrait;

    /**
     * {@inheritdoc}
     */
    public function generate(AbstractResponseConfiguration $responseConfiguration, ControllerResultInterface $controllerResult, Request $request): Response
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($responseConfiguration, $controllerResult, $request)) {
                return $strategy->generate($responseConfiguration, $controllerResult, $request);
            }
        }

        throw new GeneratorException(\sprintf(
            'No registered response generator strategy supports the response configuration "%s" within the current context.',
            \get_class($responseConfiguration)
        ));
    }
}
