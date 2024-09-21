<?php

namespace App\Repository;

use App\Entity\FollowType;
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

    /**
     * @return QueryBuilder A query builder object containing the base query to find MealList.
     */
    public function getFindOnesBaseQuery($connectedUser)
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.author', 'a')
            ->leftJoin('a.followPropositionsSent', 'fps')
            ->leftJoin('fps.type', 'ft')
            ->andWhere('m.author = :connectedUser OR a.doShowWrittenMealListToOthers = :true
                    OR (fps.follower = :connectedUser AND fps.acceptedAt IS NOT NULL)')
            ->andWhere('ft.code = :followTypeMealList OR ft.code is null')
            ->setParameter('connectedUser', $connectedUser)
            ->setParameter('followTypeMealList', FollowType::MEAL_LIST)
            ->setParameter('true', true);
        ;
    }

    /**
     * @return MealList[] Returns an array of past MealList
     */
    public function findPastOnes($connectedUser)
    {
        $now = new DateTime();

        return $this->getFindOnesBaseQuery($connectedUser)
            ->andWhere('m.endDate < :now')
            ->setParameter('now', $now)
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

        return $this->getFindOnesBaseQuery($connectedUser)
            ->andWhere(':now >= m.startDate and :now <= m.endDate')
            ->setParameter('now', $now)
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

        return $this->getFindOnesBaseQuery($connectedUser)
            ->andWhere('m.startDate > :now')
            ->setParameter('now', $now)
            ->orderBy('m.startDate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
