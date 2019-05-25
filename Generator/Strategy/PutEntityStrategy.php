<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractDataConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\PutEntity as PutEntityConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerException;
use Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayInterface;
use Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface;
use Opportus\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The put entity strategy.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Strategy
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class PutEntityStrategy implements DataStrategyInterface
{
    /**
     * @var Symfony\Component\Serializer\SerializerInterface $serializer
     */
    private $serializer;

    /**
     * @var Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    private $validator;

    /**
     * @var Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     */
    private $authorizationChecker;

    /**
     * @var Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayInterface $entityGateway
     */
    private $entityGateway;

    /**
     * @var Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface $queryBuilder
     */
    private $queryBuilder;

    /**
     * @var Opportus\ObjectMapper\ObjectMapperInterface $objectMapper
     */
    private $objectMapper;

    /**
     * Constructs the put entity strategy.
     *
     * @param Symfony\Component\Serializer\SerializerInterface $serializer
     * @param Symfony\Component\Validator\Validator\ValidatorInterface $validator
     * @param Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     * @param Opportus\ExtendedFrameworkBundle\EntityGateway\EntityGatewayInterface $entityGateway
     * @param Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryBuilderInterface $queryBuilder
     * @param Opportus\ObjectMapper\ObjectMapperInterface $objectMapper
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
     * @throws Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerException On HTTP client errors
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
        if (!$dataConfiguration instanceof PutEntityConfiguration) {
            return false;
        }

        if (null !== $dataConfiguration->getStrategyFqcn() && self::class !== $dataConfiguration->getStrategyFqcn()) {
            return false;
        }

        if (!\in_array($request->getMethod(), ['PUT'])) {
            return false;
        }

        return true;
    }
}
