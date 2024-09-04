<?php

namespace App\Repository;

use App\Entity\MealList;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MealList|null find($id, $lockMode = null, $lockVersion = null)
 * @method MealList|null findOneBy(array $criteria, array $orderBy = null)
 * @method MealList[]    findAll()
 * @method MealList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MealListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MealList::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(MealList $entity, bool $flush = true): void
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
    public function remove(MealList $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // TODO: create a base query and refact the three next methods
    /**
     * @return MealList[] Returns an array of past MealList
     */
    public function findPastOnes($connectedUser)
    {
        $now = new DateTime();

        return $this->createQueryBuilder('m')
            ->leftJoin('m.author', 'a')
            // Commented for notifications and follow rework
            // ->leftJoin('a.followerMealLists', 'fml')
            ->andWhere('m.endDate < :now')
            ->andWhere('m.author = :connectedUser OR a.doShowWrittenMealListToOthers = :true')
                    // OR (fml.follower = :connectedUser AND fml.acceptedAt IS NOT NULL)')
            ->setParameter('now', $now)
            ->setParameter('connectedUser', $connectedUser)
            ->setParameter('true', true)
            ->orderBy('m.startDate', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return MealList[] Returns an array of currents and future MealList
     */
    public function findCurrentOnes($connectedUser)
    {
        $now = new DateTime();

        return $this->createQueryBuilder('m')
            ->leftJoin('m.author', 'a')
            // Commented for notifications and follow rework
            // ->leftJoin('a.followerMealLists', 'fml')
            ->andWhere(':now >= m.startDate and :now <= m.endDate')
            ->andWhere('m.author = :connectedUser OR a.doShowWrittenMealListToOthers = :true')
                    // OR (fml.follower = :connectedUser AND fml.acceptedAt IS NOT NULL)')
            ->setParameter('now', $now)
            ->setParameter('connectedUser', $connectedUser)
            ->setParameter('true', true)
            ->orderBy('m.startDate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return MealList[] Returns an array of currents and future MealList
     */
    public function findFutureOnes($connectedUser)
    {
        $now = new DateTime();

        return $this->createQueryBuilder('m')
            ->leftJoin('m.author', 'a')
            // Commented for notifications and follow rework
            // ->leftJoin('a.followerMealLists', 'fml')
            ->andWhere('m.startDate > :now')
            ->andWhere('m.author = :connectedUser OR a.doShowWrittenMealListToOthers = :true')
                // OR (fml.follower = :connectedUser AND fml.acceptedAt IS NOT NULL)')
            ->setParameter('now', $now)
            ->setParameter('connectedUser', $connectedUser)
            ->setParameter('true', true)
            ->orderBy('m.startDate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
