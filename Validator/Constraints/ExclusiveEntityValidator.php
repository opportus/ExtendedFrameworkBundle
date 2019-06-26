<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\Validator\Constraints;

use Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayInterface;
use Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * The exclusive entity constraint validator.
 *
 * @package Opportus\ExtendedFrameworkBundle\Validator\Constraints
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ExclusiveEntityValidator extends ConstraintValidator
{
    /**
     * @var EntityGatewayInterface $entityGateway
     */
    private $entityGateway;

    /**
     * @var QueryBuilderInterface $queryBuilder
     */
    private $queryBuilder;

    /**
     * Constructs the exclusive entity constraint validator.
     *
     * @param EntityGatewayInterface $entityGateway
     * @param QueryBuilderInterface $queryBuilder
     */
    public function __construct(EntityGatewayInterface $entityGateway, QueryBuilderInterface $queryBuilder)
    {
        $this->entityGateway = $entityGateway;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ExclusiveEntity) {
            throw new UnexpectedTypeException($constraint, ExclusiveEntity::class);
        }

        $query = $this->queryBuilder
            ->prepareQuery()
            ->setEntityFqcn($constraint->entityFqcn)
            ->setCriteria(\sprintf('%s = %s', $constraint->key, \is_numeric($value) ? $value : \sprintf('"%s"', $value)))
            ->build()
        ;

        $queryResult = $this->entityGateway->query($query);

        if ($queryResult->isEmpty()) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('%key%', $constraint->key)
            ->setParameter('%value%', $value)
            ->addViolation()
        ;
    }
}
