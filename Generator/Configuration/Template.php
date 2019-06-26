<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Configuration;

use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;

/**
 * The template configuration.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 *
 * @Annotation
 * @Target("ANNOTATION")
 */
final class Template extends AbstractViewConfiguration
{
    /**
     * @var string $templateName
     */
    private $templateName;

    /**
     * Constructs the template configuration.
     *
     * @param array $values
     * @throws GeneratorException
     */
    public function __construct(array $values = [])
    {
        $this->templateName = $values['templateName'] ?? null;
        $format = $values['format'] ?? null;
        $options = $values['options'] ?? [];
        $strategyFqcn = $values['strategyFqcn'] ?? null;

        parent::__construct([
            'format' => $format,
            'options' => $options,
            'strategyFqcn' => $strategyFqcn,
        ]);

        if (!\is_string($this->templateName)) {
            throw new GeneratorException(\sprintf(
                '"templateName" is expected to be a "string", got a "%s".',
                \gettype($this->templateName)
            ));
        }
    }

    /**
     * Gets the template name.
     *
     * @return string
     */
    public function getTemplateName(): string
    {
        return $this->templateName;
    }
}
