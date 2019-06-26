<?php

namespace Opportus\ExtendedFrameworkBundle\EntityGateway;

use Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryInterface;
use Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryResultInterface;
use Opportus\ExtendedFrameworkBundle\EntityGateway\Query\QueryResult;
use Doctrine\ORM\EntityManagerInterface;

/**
 * The Doctrine entity gateway.
 *
 * @package Opportus\ExtendedFrameworkBundle\EntityGateway
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/ExtendedFrameworkBundle/blob/master/LICENSE MIT
 */
final class DoctrineEntityGateway implements EntityGatewayInterface
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * Constructs the Doctrine entity gateway.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function query(QueryInterface $query): QueryResultInterface
    {
        $dql = \sprintf('SELECT e FROM %s e', $query->getEntityFqcn());

        if (!$query->getCriteria()->isEmpty()) {
            $expression = \str_replace('"', '\'', $query->getCriteria()->toString('e.'));
            $dql .= \sprintf(' WHERE %s', $expression);
        }

        $order = '';
        foreach ($query->getOrder() as $entityPropertyName => $sortOrder) {
            if ($order) {
                $order .= ', ';
            }

            $order .= \sprintf('e.%s %s', $entityPropertyName, $sortOrder);
        }

        if ($order) {
            $dql .= \sprintf(' ORDER BY %s', $order);
        }

        try {
            $result = $this->entityManager
                ->createQuery($dql)
                ->setFirstResult($query->getOffset())
                ->setMaxResults($query->getLimit())
                ->getResult()
            ;

        } catch (\Exception $exception) {
            throw new EntityGatewayException(
                \sprintf(
                    'Invalid "%s" operation on entity "%s".',
                    __METHOD__,
                    $query->getEntityFqcn()
                ),
                0,
                $exception
            );
        }

        return new QueryResult($result);
    }

    /**
     * {@inheritdoc}
     */
    public function save(object $entity)
    {
        try {
            $this->entityManager->persist($entity);
        } catch (\Exception $exception) {
            throw new EntityGatewayException(
                \sprintf(
                    'Invalid "%s" operation on entity "%s".',
                    __METHOD__,
                    \get_class($entity)
                ),
                0,
                $exception
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(object $entity)
    {
        try {
            $this->entityManager->remove($entity);
        } catch (\Exception $exception) {
            throw new EntityGatewayException(
                \sprintf(
                    'Invalid "%s" operation on entity "%s".',
                    __METHOD__,
                    \get_class($entity)
                ),
                0,
                $exception
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        try {
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            throw new EntityGatewayException(
                \sprintf(
                    'Invalid "%s" operation.',
                    __METHOD__
                ),
                0,
                $exception
            );
        }
    }
}
