<?php

namespace App\Repository;

use App\Entity\NotificationHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NotificationHistory>
 *
 * @method NotificationHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotificationHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotificationHistory[]    findAll()
 * @method NotificationHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationHistory::class);
    }

//    /**
//     * @return NotificationHistory[] Returns an array of NotificationHistory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NotificationHistory
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
