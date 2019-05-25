<?php

namespace Opportus\ExtendedFrameworkBundle\Generator;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractFlashConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The flash generator.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class FlashGenerator implements FlashGeneratorInterface
{
    use GeneratorTrait;

    /**
     * {@inheritdoc}
     */
    public function generate(AbstractFlashConfiguration $flashConfiguration, ControllerResultInterface $controllerResult, Request $request)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($flashConfiguration, $controllerResult, $request)) {
                $strategy->generate($flashConfiguration, $controllerResult, $request);
                return;
            }
        }

        throw new GeneratorException(\sprintf(
            'No registered flash generator strategy supports the flash configuration "%s" within the current context.',
            \get_class($flashConfiguration)
        ));
    }
}
