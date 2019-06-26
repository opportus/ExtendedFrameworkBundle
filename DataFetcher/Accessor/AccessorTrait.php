<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor;

use Opportus\ExtendedFrameworkBundle\DataFetcher\DataFetcherException;

/**
 * The accessor trait.
 *
 * @package Opportus\ExtendedFrameworkBundle\DataFetcher\Accessor
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
trait AccessorTrait
{
    /**
     * @var string $name
     */
    private $name;

    /**
     * Constructs the accessor.
     *
     * @param array $values
     * @throws DataFetcherException
     */
    public function __construct(array $values = [])
    {
        $this->name = $values['name'] ?? null;

        if (!\is_string($this->name)) {
            throw new DataFetcherException(\sprintf(
                '"name" is expected to be a "string", got a "%s".',
                \gettype($this->name)
            ));
        }
    }

    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets the annotation alias.
     *
     * @return string
     */
    public function getAnnotationAlias(): string
    {
        return 'data_accessor';
    }
}
