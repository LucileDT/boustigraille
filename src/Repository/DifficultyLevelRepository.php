<?php

namespace App\Repository;

use App\Entity\DifficultyLevel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DifficultyLevel>
 *
 * @method DifficultyLevel|null find($id, $lockMode = null, $lockVersion = null)
 * @method DifficultyLevel|null findOneBy(array $criteria, array $orderBy = null)
 * @method DifficultyLevel[]    findAll()
 * @method DifficultyLevel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DifficultyLevelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DifficultyLevel::class);
    }

//    /**
//     * @return DifficultyLevel[] Returns an array of DifficultyLevel objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DifficultyLevel
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
