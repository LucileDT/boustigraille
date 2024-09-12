<?php

namespace App\Repository;

use App\Entity\FollowRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FollowRequest>
 *
 * @method FollowRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method FollowRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method FollowRequest[]    findAll()
 * @method FollowRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FollowRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FollowRequest::class);
    }

//    /**
//     * @return FollowRequest[] Returns an array of FollowRequest objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FollowRequest
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
