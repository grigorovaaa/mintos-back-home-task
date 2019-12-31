<?php

namespace App\Repository;

use App\Entity\ExternalFeed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 */
class ExternalFeedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExternalFeed::class);
    }

    /**
     * @param $value
     * @return ExternalFeed|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneLastBySource($value): ?ExternalFeed
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.source = :source')
            ->setParameter('source', $value)
            ->orderBy('e.updated', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
