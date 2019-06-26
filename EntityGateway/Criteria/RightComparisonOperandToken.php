<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\EntityGateway\Criteria;

use Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayException;

/**
 * The right comparison operand token.
 *
 * @package Opportus\ExtendedFrameworkBundle\EntityGateway\Criteria
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE MIT
 */
final class RightComparisonOperandToken implements TokenInterface
{
    const LEXEME_PATTERN = '".*"|(-?(?:\d+|\d*\.\d+))|(TRUE|FALSE|NULL)(?i)';

    /**
     * @var string $value
     */
    private $value;

    /**
     * Constructs the right comparison operand token.
     *
     * @param string $lexeme
     * @throws EntityGatewayException If the lexeme does not match the pattern
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
