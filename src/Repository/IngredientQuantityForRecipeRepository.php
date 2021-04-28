<?php

namespace App\Repository;

use App\Entity\IngredientQuantityForRecipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IngredientQuantityForRecipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method IngredientQuantityForRecipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method IngredientQuantityForRecipe[]    findAll()
 * @method IngredientQuantityForRecipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientQuantityForRecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IngredientQuantityForRecipe::class);
    }

    // /**
    //  * @return IngredientQuantityForRecipe[] Returns an array of IngredientQuantityForRecipe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IngredientQuantityForRecipe
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
