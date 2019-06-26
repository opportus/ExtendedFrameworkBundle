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
 * The criteria.
 *
 * @package Opportus\ExtendedFrameworkBundle\EntityGateway\Criteria
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE MIT
 */
final class Criteria implements CriteriaInterface
{
    /**
     * @var TokenInterface[] $tokenSequence
     */
    private $tokenSequence;

    /**
     * Constructs the criteria.
     *
     * @param string $expression
     */
    public function __construct(string $expression)
    {
        $this->tokenSequence = $this->tokenize($expression);
    }

    /**
     * Tokenizes.
     *
     * @param string $expression
     * @return array The token sequence
     * @throws EntityGatewayException
     */
    private function tokenize(string $expression): array
    {
        // Normalizes the expression into a lexeme sequence...
        $lexemeSequence = \array_values(\array_filter(
            \array_map(
                function ($lexeme) {
                    return \trim($lexeme);
                },
                \preg_split('/('.LogicalOperatorToken::LEXEME_PATTERN.'|'.ComparisonOperatorToken::LEXEME_PATTERN.'|'.ParenthesisToken::LEXEME_PATTERN.')/', $expression, -1, \PREG_SPLIT_DELIM_CAPTURE)
            ),
            function ($lexeme) {
                if ('' !== $lexeme) {
                    return true;
                }
            }
        ));

        // Confirms that the expression contains as many open parentheses than close parentheses...
        if (\count(\array_keys($lexemeSequence, '(')) !== \count(\array_keys($lexemeSequence, ')'))) {
            throw new EntityGatewayException(
                \sprintf(
                    'Invalid "expression" argument: expecting an expression to contain as many open parentheses than close parentheses in "%s".',
                    $expression
                )
            );
        }

        $tokenSequence = [];

        // Tokenizes each lexeme if its preceding and following lexemes match the expected pattern, otherwise throws an exception...
        // This way it validates the syntax and integrity of the expression.
        foreach ($lexemeSequence as $position => $lexeme) {
            if (\preg_match('/^'.ParenthesisToken::LEXEME_PATTERN.'$/', $lexeme)) {
                if ('(' === $lexeme) {
                    if (isset($lexemeSequence[$position-1])) {
                        if (!\preg_match('/^\(|'.LogicalOperatorToken::LEXEME_PATTERN.'$/', $lexemeSequence[$position-1])) {
                            throw new EntityGatewayException(\sprintf(
                                'Invalid "expression" argument: expecting an open parenthesis to be preceded by either another open parenthesis or a logical operator in "%s", got "%s" as token number "%d".',
                                $expression,
                                $lexemeSequence[$position-1],
                                $position-1
                            ));
                        }
                    }

                    if (!isset($lexemeSequence[$position+1])) {
                        throw new EntityGatewayException(\sprintf(
                            'Invalid "expression" argument: expecting an open parenthesis to be followed by either another open parenthesis or a left comparison operand in "%s", got "" as token number "%d".',
                            $expression,
                            $position+1
                        ));
                    }

                    if (!\preg_match('/^\(|'.LeftComparisonOperandToken::LEXEME_PATTERN.'$/', $lexemeSequence[$position+1])) {
                        throw new EntityGatewayException(\sprintf(
                            'Invalid "expression" argument: expecting an open parenthesis to be followed by either another open parenthesis or a left comparison operand in "%s", got "%s" as token number "%d".',
                            $expression,
                            $lexemeSequence[$position+1],
                            $position+1
                        ));
                    }
                } elseif (')' === $lexeme) {
                    if (!isset($lexemeSequence[$position-1])) {
                        throw new EntityGatewayException(\sprintf(
                            'Invalid "expression" argument: expecting a close parenthesis to be preceded by either another close parenthesis or a right comparison operand in "%s", got "" as token number "%d".',
                            $expression,
                            $position-1
                        ));
                    }

                    if (!\preg_match('/^\)|'.RightComparisonOperandToken::LEXEME_PATTERN.'$/', $lexemeSequence[$position-1])) {
                        throw new EntityGatewayException(\sprintf(
                            'Invalid "expression" argument: expecting a close parenthesis to be preceded by either another close parenthesis or a right comparison operand in "%s", got "%s" as token number "%d".',
                            $expression,
                            $lexemeSequence[$position-1],
                            $position-1
                        ));
                    }

                    if (isset($lexemeSequence[$position+1])) {
                        if (!\preg_match('/^\)|'.LogicalOperatorToken::LEXEME_PATTERN.'$/', $lexemeSequence[$position+1])) {
                            throw new EntityGatewayException(\sprintf(
                                'Invalid "expression" argument: expecting a close parenthesis to be followed by either another close parenthesis or a logical operator in "%s", got "%s" as token number "%d".',
                                $expression,
                                $lexemeSequence[$position+1],
                                $position+1
                            ));
                        }
                    }
                }

                $tokenSequence[$position] = new ParenthesisToken($lexeme);
            } elseif (\preg_match('/^'.LogicalOperatorToken::LEXEME_PATTERN.'$/', $lexeme)) {
                if (!isset($lexemeSequence[$position-1])) {
                    throw new EntityGatewayException(\sprintf(
                        'Invalid "expression" argument: expecting a logical operator to be preceded by either a right comparison operand in "%s", got "" as token number "%d".',
                        $expression,
                        $position-1
                    ));
                }

                if (!\preg_match('/^'.RightComparisonOperandToken::LEXEME_PATTERN.'$/', $lexemeSequence[$position-1])) {
                    throw new EntityGatewayException(\sprintf(
                        'Invalid "expression" argument: expecting a logical operator to be preceded by either a right comparison operand in "%s", got "%s" as token number "%d".',
                        $expression,
                        $lexemeSequence[$position-1],
                        $position-1
                    ));
                }

                if (!isset($lexemeSequence[$position+1])) {
                    throw new EntityGatewayException(\sprintf(
                        'Invalid "expression" argument: expecting a logical operator to be followed by either an open parenthesis or a left comparison operand in "%s", got "" as token number "%d".',
                        $expression,
                        $position+1
                    ));
                }

                if (!\preg_match('/^'.ParenthesisToken::LEXEME_PATTERN.'|'.LeftComparisonOperandToken::LEXEME_PATTERN.'$/', $lexemeSequence[$position+1])) {
                    throw new EntityGatewayException(\sprintf(
                        'Invalid "expression" argument: expecting a logical operator to be followed by either an open parenthesis or a left comparison operand in "%s", got "%s" as token number "%d".',
                        $expression,
                        $lexemeSequence[$position+1],
                        $position+1
                    ));
                }

                $tokenSequence[$position] = new LogicalOperatorToken($lexeme);
            } elseif (\preg_match('/^'.ComparisonOperatorToken::LEXEME_PATTERN.'$/', $lexeme)) {
                if (!isset($lexemeSequence[$position-1])) {
                    throw new EntityGatewayException(\sprintf(
                        'Invalid "expression" argument: expecting a comparison operator to be preceded by a left comparison operand in "%s", got "" as token number "%d".',
                        $expression,
                        $position-1
                    ));
                }

                if (!\preg_match('/^'.LeftComparisonOperandToken::LEXEME_PATTERN.'$/', $lexemeSequence[$position-1])) {
                    throw new EntityGatewayException(\sprintf(
                        'Invalid "expression" argument: expecting a comparison operator to be preceded by a left comparison operand in "%s", got "%s" as token number "%d".',
                        $expression,
                        $lexemeSequence[$position-1],
                        $position-1
                    ));
                }

                if (!isset($lexemeSequence[$position+1])) {
                    throw new EntityGatewayException(\sprintf(
                        'Invalid "expression" argument: expecting a comparison operator to be followed by either a right comparison operand in "%s", got "" as token number "%d".',
                        $expression,
                        $position+1
                    ));
                }

                if (!\preg_match('/^'.RightComparisonOperandToken::LEXEME_PATTERN.'$/', $lexemeSequence[$position+1])) {
                    throw new EntityGatewayException(\sprintf(
                        'Invalid "expression" argument: expecting a comparison operator to be followed by either a right comparison operand in "%s", got "%s" as token number "%d".',
                        $expression,
                        $lexemeSequence[$position+1],
                        $position+1
                    ));
                }

                $tokenSequence[$position] = new ComparisonOperatorToken($lexeme);
            } elseif (\preg_match('/^'.LeftComparisonOperandToken::LEXEME_PATTERN.'$/', $lexeme)) {
                if (isset($lexemeSequence[$position-1])) {
                    if (!\preg_match('/^'.ParenthesisToken::LEXEME_PATTERN.'|'.LogicalOperatorToken::LEXEME_PATTERN.'$/', $lexemeSequence[$position-1])) {
                        throw new EntityGatewayException(\sprintf(
                            'Invalid "expression" argument: expecting a left comparison operand to be preceded by either an open parenthesis or a logical operator in "%s", got "%s" as token number "%d".',
                            $expression,
                            $lexemeSequence[$position-1],
                            $position-1
                        ));
                    }
                }

                if (!isset($lexemeSequence[$position+1])) {
                    throw new EntityGatewayException(\sprintf(
                        'Invalid "expression" argument: expecting a left comparison operand to be followed by a comparison operator in "%s", got "" as token number "%d".',
                        $expression,
                        $position+1
                    ));
                }

                if (!\preg_match('/^'.ComparisonOperatorToken::LEXEME_PATTERN.'$/', $lexemeSequence[$position+1])) {
                    throw new EntityGatewayException(\sprintf(
                        'Invalid "expression" argument: expecting a left comparison operand to be followed by a comparison operator in "%s", got "%s" as token number "%d".',
                        $expression,
                        $lexemeSequence[$position+1],
                        $position+1
                    ));
                }

                $tokenSequence[$position] = new LeftComparisonOperandToken($lexeme);
            } elseif (\preg_match('/^'.RightComparisonOperandToken::LEXEME_PATTERN.'$/', $lexeme)) {
                if (!isset($lexemeSequence[$position-1])) {
                    throw new EntityGatewayException(\sprintf(
                        'Invalid "expression" argument: expecting a right comparison operand to be preceded by either a comparison operator in "%s", got "" as token number "%d".',
                        $expression,
                        $position-1
                    ));
                }

                if (!\preg_match('/^'.ComparisonOperatorToken::LEXEME_PATTERN.'$/', $lexemeSequence[$position-1])) {
                    throw new EntityGatewayException(\sprintf(
                        'Invalid "expression" argument: expecting a right comparison operand to be preceded by either a comparison operator in "%s", got "%s" as token number "%d".',
                        $expression,
                        $lexemeSequence[$position-1],
                        $position-1
                    ));
                }

                if (isset($lexemeSequence[$position+1])) {
                    if (!\preg_match('/^'.ParenthesisToken::LEXEME_PATTERN.'|'.LogicalOperatorToken::LEXEME_PATTERN.'$/', $lexemeSequence[$position+1])) {
                        throw new EntityGatewayException(\sprintf(
                            'Invalid "expression" argument: expecting a right comparison operand to be followed by either a close parenthesis or a logical operator in "%s", got "%s" as token number "%d".',
                            $expression,
                            $lexemeSequence[$position+1],
                            $position+1
                        ));
                    }
                }

                $tokenSequence[$position] = new RightComparisonOperandToken($lexeme);
            } else {
                throw new EntityGatewayException(
                    \sprintf(
                        'Invalid "expression" argument: expecting an expression to contain valid tokens in "%s", got "%s".',
                        $expression,
                        $lexeme
                    )
                );
            }
        }

        return $tokenSequence;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(string $leftComparisonOperandPrefix = ''): string
    {
        $string = '';
        foreach ($this->tokenSequence as $position => $token) {
            if (0 !== $position) {
                $string .= ' ';
            }

            if ($token instanceof LeftComparisonOperandToken) {
                $string .= $leftComparisonOperandPrefix;
            }

            $string .= $token->getValue();
        }

        return $string;
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty(): bool
    {
        return empty($this->tokenSequence);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->tokenSequence);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->tokenSequence[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->tokenSequence[$offset];
    }

    /**
     * {@inheritDoc}
     *
     * @throws EntityGatewayException
     */
    public function offsetSet($offset, $value)
    {
        throw new EntityGatewayException(\sprintf(
            'Invalid "%s" operation: attempting to set an element of an immutable array.',
            __METHOD__
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @throws EntityGatewayException
     */
    public function offsetUnset($offset)
    {
        throw new EntityGatewayException(\sprintf(
            'Invalid "%s" operation: attempting to unset an element of an immutable array.',
            __METHOD__
        ));
    }
}
