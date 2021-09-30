<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Flow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Flow|null find($id, $lockMode = null, $lockVersion = null)
 * @method Flow|null findOneBy(array $criteria, array $orderBy = null)
 * @method Flow[]    findAll()
 * @method Flow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Flow::class);
    }

    /**
     * @return Flow[] Returns an array of Flow objects
     */
    public function findLastStationFlows(int $stationId): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.stationId = :stationId')
            ->setParameter('stationId', $stationId)
            ->orderBy('f.datetime', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }
}
