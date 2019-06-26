<?php

namespace Opportus\ExtendedFrameworkBundle\Generator;

/**
 * The generator trait.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
trait GeneratorTrait
{
    /**
     * @var array $strategies
     */
    private $strategies;

    /**
     * Constructs the generator.
     *
     * @param array $strategies
     */
    public function __construct(array $strategies)
    {
        $fqcn = \get_class($this);
        $nfqcn = \substr($fqcn, \strrpos($fqcn, '\\')+1);
        $strategyInterfaceNfqcn = \str_replace('Generator', 'StrategyInterface', $nfqcn);
        $strategyInterfaceFqcn = \str_replace($nfqcn, \sprintf('Strategy\%s', $strategyInterfaceNfqcn), $fqcn);

        foreach ($strategies as $strategy) {
            if (!\is_object($strategy)) {
                throw new GeneratorException(\sprintf(
                    '"strategies" are expected to be objects, got a "%s".',
                    \gettype($strategy)
                ));
            }

            if (!\is_subclass_of($strategy, $strategyInterfaceFqcn)) {
                throw new GeneratorException(\sprintf(
                    '"%s" "strategies" are expected to implement "%s", got an instance of "%s".',
                    \get_class($this),
                    $strategyInterfaceFqcn,
                    \get_class($strategy)
                ));
            }
        }

        $this->strategies = $strategies;
    }
}
