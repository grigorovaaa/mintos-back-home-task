<?php

namespace App\Repository;

use App\Entity\ExternalFeedKeyWord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ExternalFeedKeyWord|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExternalFeedKeyWord|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExternalFeedKeyWord[]    findAll()
 * @method ExternalFeedKeyWord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExternalFeedKeyWordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExternalFeedKeyWord::class);
    }

    // /**
    //  * @return ExternalFeedKeyWord[] Returns an array of ExternalFeedKeyWord objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExternalFeedKeyWord
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
