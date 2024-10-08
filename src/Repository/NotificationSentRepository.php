<?php

namespace App\Repository;

use App\Entity\NotificationSent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NotificationSent>
 *
 * @method NotificationSent|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotificationSent|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotificationSent[]    findAll()
 * @method NotificationSent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationSentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationSent::class);
    }

//    /**
//     * @return NotificationSent[] Returns an array of NotificationSent objects
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

//    public function findOneBySomeField($value): ?NotificationSent
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
