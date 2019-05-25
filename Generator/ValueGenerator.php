<?php

namespace Opportus\ExtendedFrameworkBundle\Generator;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractValueConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The value generator.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class ValueGenerator implements ValueGeneratorInterface
{
    use GeneratorTrait;

    /**
     * {@inheritdoc}
     */
    public function generate(AbstractValueConfiguration $valueConfiguration, ControllerResultInterface $controllerResult, Request $request): string
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($valueConfiguration, $controllerResult, $request)) {
                return $strategy->generate($valueConfiguration, $controllerResult, $request);
            }
        }

        throw new GeneratorException(\sprintf(
            'No registered value generator strategy supports the value configuration "%s" within the current context.',
            \get_class($valueConfiguration)
        ));
    }
}
