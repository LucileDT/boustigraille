<?php

namespace App\Repository;

use App\Entity\Ingredient;
use DateInterval;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredient[]    findAll()
 * @method Ingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    /**
     * @return Ingredient[] Returns an array of Ingredient objects
     */
    public function findByFetchedFromOFFDuringLastMinute(): array
    {
        $now = new DateTime();
        return $this->createQueryBuilder('i')
            ->andWhere('i.lastSynchronizedAt > :oneMinuteAgo')
            ->setParameter('oneMinuteAgo', $now->sub(new DateInterval('PT1M')))
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Ingredient[] Returns an array of Ingredient objects
     */
    public function findByLastSynchronizedAtNull(): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.lastSynchronizedAt IS NULL')
            ->andWhere('i.barCode IS NOT NULL')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Ingredient[] Returns an array of Ingredient objects
     */
    public function findByLastSynchronizedNotNull(int $maxResults): array
    {
        $now = new DateTime();
        return $this->createQueryBuilder('i')
            ->andWhere('i.lastSynchronizedAt IS NOT NULL')
            ->andWhere('i.lastSynchronizedAt < :oneMinuteAgo')
            ->setParameter('oneMinuteAgo', $now->sub(new DateInterval('PT1M')))
            ->andWhere('i.barCode IS NOT NULL')
            ->orderBy('i.lastSynchronizedAt', 'ASC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }
}
