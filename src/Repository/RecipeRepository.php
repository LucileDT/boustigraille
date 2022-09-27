<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function findByFavedByAndName(User $user, string $name = null) {
        $query = $this->createQueryBuilder('r')
            ->join('r.favedBy', 'u')
            ->andWhere('u = :user')
            ->setParameter('user', $user)
            ;
        if (!empty($name)) {
            $query->andWhere('r.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }
        return $query->orderBy('r.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByNotFavedByAndName(User $user, string $name = null) {
        $query = $this->createQueryBuilder('r')
            ->andWhere(':user NOT MEMBER OF r.favedBy')
            ->setParameter('user', $user)
            ;
        if (!empty($name)) {
            $query->andWhere('r.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }
        return $query->orderBy('r.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findFavoritedRecipesNeverMade(User $user, int $limit = 6)
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.mealQuantityForLists', 'mqfl')
            ->join('r.favedBy', 'u')
            ->andWhere('u = :user')
            ->andWhere('mqfl.id IS NULL')
            ->setParameter('user', $user)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findFavoritedRecipesOldestMade(User $user, int $limit = 6)
    {
        return $this->createQueryBuilder('recipe')
            ->select('recipe')
            ->addSelect('max(meal_list.endDate) AS HIDDEN maxdate')
            ->from('App:MealQuantityForList', 'meal_quantity_for_list')
            ->from('App:MealList', 'meal_list')
            ->andWhere('meal_list.id = meal_quantity_for_list.mealList')
            ->andWhere('recipe.id = meal_quantity_for_list.meal')
            ->join('recipe.favedBy', 'u')
            ->andWhere('u = :user')
            ->setParameter('user', $user)
            ->groupBy('recipe.id')
            ->orderBy('maxdate', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Recipe[] Returns an array of Recipe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recipe
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
