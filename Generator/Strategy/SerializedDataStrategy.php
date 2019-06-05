<?php

namespace Opportus\ExtendedFrameworkBundle\Generator\Strategy;

use Opportus\ExtendedFrameworkBundle\Generator\GeneratorException;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\AbstractViewConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Configuration\SerializedData as SerializedDataConfiguration;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerResultInterface;
use Opportus\ExtendedFrameworkBundle\Generator\Context\ControllerException;
use Opportus\ExtendedFrameworkBundle\DataFetcher\DataFetcherInterface;
use Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryResult;
use Opportus\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * The serialized data strategy.
 *
 * @package Opportus\ExtendedFrameworkBundle\Generator\Strategy
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE.md MIT
 */
final class SerializedDataStrategy implements ViewStrategyInterface
{
    /**
     * @var Symfony\Component\Serializer\SerializerInterface $serializer
     */
    private $serializer;

    /**
     * @var Opportus\ExtendedFrameworkBundle\DataFetcher\DataFetcherInterface $dataFetcher
     */
    private $dataFetcher;

    /**
     * @var Opportus\ObjectMapper\ObjectMapperInterface $objectMapper
     */
    private $objectMapper;

    /**
     * Constructs the serialized data strategy.
     *
     * @param Symfony\Component\Serializer\SerializerInterface $serializer
     * @param Opportus\ExtendedFrameworkBundle\DataFetcher\DataFetcherInterface $dataFetcher
     * @param Opportus\ObjectMapper\ObjectMapperInterface $objectMapper
     */
    public function __construct(SerializerInterface $serializer, DataFetcherInterface $dataFetcher, ObjectMapperInterface $objectMapper)
    {
        $this->serializer = $serializer;
        $this->dataFetcher = $dataFetcher;
        $this->objectMapper = $objectMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(AbstractViewConfiguration $viewConfiguration, ControllerResultInterface $controllerResult, Request $request): string
    {
        if (false === $this->supports($viewConfiguration, $controllerResult, $request)) {
            throw new GeneratorException(\sprintf(
                '"%s" does not support the view configuration within the current context.',
                self::class
            ));
        }

        $accessor = $viewConfiguration->getAccessor();

        if (null === $accessor) {
            $data = $controllerResult->getData();

        } else {
            $data = $this->dataFetcher->fetch($accessor, $controllerResult->getData());
        }

        $data = $data ?? [];
        $format = $request->getFormat($viewConfiguration->getFormat());
        $context = $viewConfiguration->getSerializationContext();
        $serializationFqcn = $viewConfiguration->getSerializationFqcn();

        if (\is_object($data)) {
            if ($data instanceof QueryResult) {
                if (null === $serializationFqcn) {
                    $data = $data->toArray();

                } else {
                    $serializableItems = [];
                    foreach ($data as $entity) {
                        $serializableItems[] = $this->objectMapper->map($entity, $serializationFqcn);
                    }

                    $data = $serializableItems;
                }

            } elseif (null !== $serializationFqcn) {
                $data = $this->objectMapper->map($data, $serializationFqcn);
            }
        }

        return $this->serializer->serialize($data, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(AbstractViewConfiguration $viewConfiguration, ControllerResultInterface $controllerResult, Request $request): bool
    {
        if (!$viewConfiguration instanceof SerializedDataConfiguration) {
            return false;
        }

        if (null !== $viewConfiguration->getStrategyFqcn() && self::class !== $viewConfiguration->getStrategyFqcn()) {
            return false;
        }

        return true;
    }
}
