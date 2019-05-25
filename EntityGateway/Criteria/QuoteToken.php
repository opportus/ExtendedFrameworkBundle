<?php

namespace Opportus\ExtendedFrameworkBundle\EntityGateway\Criteria;

use Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayException;

/**
 * The quote token.
 *
 * @package Opportus\ExtendedFrameworkBundle\EntityGateway\Criteria
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE MIT
 */
final class QuoteToken implements TokenInterface
{
    const LEXEME_PATTERN = '"';

    /**
     * @var string $value
     */
    private $value;

    /**
     * Constructs the quote token.
     *
     * @param string $lexeme
     * @throws Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayException If the lexeme does not match the pattern
     */
    public function __construct(string $lexeme)
    {
        if (!\preg_match('/^'.self::LEXEME_PATTERN.'$/', $lexeme)) {
            throw new EntityGatewayException(\sprintf(
                'Invalid "lexeme" argument: expecting the lexeme to match "%s", got "%s".',
                '/^'.self::LEXEME_PATTERN.'$/',
                $lexeme
            ));
        }

        $this->value = $lexeme;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
