<?php

namespace App\Repository;

use App\Entity\FollowType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FollowType>
 *
 * @method FollowType|null find($id, $lockMode = null, $lockVersion = null)
 * @method FollowType|null findOneBy(array $criteria, array $orderBy = null)
 * @method FollowType[]    findAll()
 * @method FollowType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FollowTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FollowType::class);
    }

//    /**
//     * @return FollowType[] Returns an array of FollowType objects
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

//    public function findOneBySomeField($value): ?FollowType
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
