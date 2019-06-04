<?php

namespace Opportus\ExtendedFrameworkBundle\Generator;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractViewConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The view generator.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class ViewGenerator implements ViewGeneratorInterface
{
    use GeneratorTrait;

    /**
     * {@inheritdoc}
     */
    public function generate(AbstractViewConfiguration $viewConfiguration, ControllerResultInterface $controllerResult, Request $request): string
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($viewConfiguration, $controllerResult, $request)) {
                return $strategy->generate($viewConfiguration, $controllerResult, $request);
            }
        }

        throw new GeneratorException(\sprintf(
            'No registered value generator strategy supports the value configuration "%s" within the current context.',
            \get_class($viewConfiguration)
        ));
    }
}
