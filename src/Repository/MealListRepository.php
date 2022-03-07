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

    /**
     * @return MealList[] Returns an array of past MealList
     */
    public function findPastOnes()
    {
        $now = new DateTime();

        return $this->createQueryBuilder('m')
            ->where('m.endDate < :now')
            ->setParameter('now', $now->format('Y-m-d'))
            ->orderBy('m.startDate', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

   /**
     * @return MealList[] Returns an array of currents and future MealList
     */
    public function findCurrentAndFutureOnes()
    {
        $now = new DateTime();

        return $this->createQueryBuilder('m')
            ->where('m.startDate >= :now')
            ->orWhere('m.endDate >= :now')
            ->setParameter('now', $now->format('Y-m-d'))
            ->orderBy('m.startDate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
