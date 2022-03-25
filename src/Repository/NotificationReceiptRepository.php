<?php

namespace App\Repository;

use App\Entity\NotificationReceipt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NotificationReceipt|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotificationReceipt|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotificationReceipt[]    findAll()
 * @method NotificationReceipt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationReceiptRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationReceipt::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(NotificationReceipt $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(NotificationReceipt $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findUnreadByUser($user)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.dateRead IS NULL')
            ->andWhere('n.recipient = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findReadByUser($user)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.dateRead IS NOT NULL')
            ->andWhere('n.recipient = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return NotificationReceipt[] Returns an array of NotificationReceipt objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NotificationReceipt
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
