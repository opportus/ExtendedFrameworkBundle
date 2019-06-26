<?php

/**
 * This file is part of the opportus/extended-framework-bundle package.
 *
 * Copyright (c) 2019 Clément Cazaud <clement.cazaud@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayInterface;
use Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractDataConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\PatchEntity as PatchEntityConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerException;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Opportus\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * The patch entity strategy.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Strategy
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class PatchEntityStrategy implements DataStrategyInterface
{
    /**
     * @var SerializerInterface $serializer
     */
    private $serializer;

    /**
     * @var ValidatorInterface $validator
     */
    private $validator;

    /**
     * @var AuthorizationCheckerInterface $authorizationChecker
     */
    private $authorizationChecker;

    /**
     * @var EntityGatewayInterface $entityGateway
     */
    private $entityGateway;

    /**
     * @var QueryBuilderInterface $queryBuilder
     */
    private $queryBuilder;

    /**
     * @var ObjectMapperInterface $objectMapper
     */
    private $objectMapper;

    /**
     * Constructs the patch entity strategy.
     *
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param EntityGatewayInterface $entityGateway
     * @param QueryBuilderInterface $queryBuilder
     * @param ObjectMapperInterface $objectMapper
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, AuthorizationCheckerInterface $authorizationChecker, EntityGatewayInterface $entityGateway, QueryBuilderInterface $queryBuilder, ObjectMapperInterface $objectMapper)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->authorizationChecker = $authorizationChecker;
        $this->entityGateway = $entityGateway;
        $this->queryBuilder = $queryBuilder;
        $this->objectMapper = $objectMapper;
    }

    /**
     * {@inheritdoc}
     *
     * @throws ControllerException On HTTP client errors
     */
    public function generate(AbstractDataConfiguration $dataConfiguration, Request $request): object
    {
        if (false === $this->supports($dataConfiguration, $request)) {
            throw new GeneratorException(\sprintf(
                '"%s" does not support the data configuration within the current context.',
                self::class
            ));
        }

        $deserializationFqcn = $dataConfiguration->getDeserializationFqcn() ?? $dataConfiguration->getValidationFqcn() ?? $dataConfiguration->getEntityFqcn();

        try {
            $deserializationObject = $this->serializer->deserialize(
                $request->getContent(),
                $deserializationFqcn,
                $request->getContentType(),
                $dataConfiguration->getDeserializationContext()
            );
        } catch (NotEncodableValueException $exception) {
            throw new ControllerException(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, null, '', 0, $exception);
        }

        $validationFqcn = $dataConfiguration->getValidationFqcn() ?? $dataConfiguration->getDeserializationFqcn();

        if ($validationFqcn !== $deserializationFqcn) {
            $validationObject = $this->objectMapper->map($deserializationObject, $validationFqcn);
        } else {
            $validationObject = $deserializationObject;
        }
        
        $constraintViolationList = $this->validator->validate($validationObject);

        if (0 !== \count($constraintViolationList)) {
            throw new ControllerException(Response::HTTP_BAD_REQUEST, $constraintViolationList, (string)$constraintViolationList);
        }

        $entityFqcn = $dataConfiguration->getEntityFqcn();
        $entityIdKey = $dataConfiguration->getEntityId() ?? 'id';
        $entityIdValue = $request->attributes->get($entityIdKey);

        if (null === $entityIdValue) {
            throw new GeneratorException(\sprintf(
                'The request does not contain any attribute "%s" in order to identify the requested entity.',
                $entityIdKey
            ));
        }

        $entityIdValue = \is_numeric($entityIdValue) ? $entityIdValue : \sprintf('"%s"', $entityIdValue);

        $queryResult = $this->entityGateway->query(
            $this->queryBuilder
            ->prepareQuery()
            ->setEntityFqcn($entityFqcn)
            ->setCriteria(\sprintf('%s = %s', $entityIdKey, $entityIdValue))
            ->build()
        );

        if ($queryResult->isEmpty()) {
            throw new ControllerException(Response::HTTP_NOT_FOUND);
        }

        $entity = $queryResult[0];

        if (null !== $dataConfiguration->getEntityAccess()) {
            if (!$this->authorizationChecker->isGranted($request->getMethod(), $entity)) {
                throw new ControllerException(Response::HTTP_FORBIDDEN);
            }
        }

        $entity = $this->objectMapper->map($validationObject, $entity);

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(AbstractDataConfiguration $dataConfiguration, Request $request): bool
    {
        if (!$dataConfiguration instanceof PatchEntityConfiguration) {
            return false;
        }

        if (null !== $dataConfiguration->getStrategyFqcn() && self::class !== $dataConfiguration->getStrategyFqcn()) {
            return false;
        }

        if (!\in_array($request->getMethod(), ['PATCH'])) {
            return false;
        }

        return true;
    }
}
