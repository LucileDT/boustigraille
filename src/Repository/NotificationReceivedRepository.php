<?php

namespace App\Repository;

use App\Entity\NotificationReceived;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NotificationReceived>
 *
 * @method NotificationReceived|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotificationReceived|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotificationReceived[]    findAll()
 * @method NotificationReceived[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationReceivedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationReceived::class);
    }

//    /**
//     * @return NotificationReceived[] Returns an array of NotificationReceived objects
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

//    public function findOneBySomeField($value): ?NotificationReceived
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
