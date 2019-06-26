<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractDataConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\PostEntity as PostEntityConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerException;
use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Opportus\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * The post entity strategy.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Strategy
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class PostEntityStrategy implements DataStrategyInterface
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
     * @var ObjectMapperInterface $objectMapper
     */
    private $objectMapper;

    /**
     * Constructs the post entity strategy.
     *
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param ObjectMapperInterface $objectMapper
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, ObjectMapperInterface $objectMapper)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
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

        if ($entityFqcn !== $validationFqcn) {
            $entity = $this->objectMapper->map($validationObject, $entityFqcn);
        } else {
            $entity = $validationObject;
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(AbstractDataConfiguration $dataConfiguration, Request $request): bool
    {
        if (!$dataConfiguration instanceof PostEntityConfiguration) {
            return false;
        }

        if (null !== $dataConfiguration->getStrategyFqcn() && self::class !== $dataConfiguration->getStrategyFqcn()) {
            return false;
        }

        if (!\in_array($request->getMethod(), ['POST'])) {
            return false;
        }

        return true;
    }
}
