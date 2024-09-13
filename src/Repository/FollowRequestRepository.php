<?php

namespace App\Repository;

use App\Entity\FollowRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FollowRequest>
 *
 * @method FollowRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method FollowRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method FollowRequest[]    findAll()
 * @method FollowRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FollowRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FollowRequest::class);
    }

    // Commented for notifications and follow rework
    // Adapt with follow type as filter
    // /**
    //  * @return FollowMealList[] / FollowUsernameOnRecipe[]
    //  */
    // public function findUserOnesAccepted($user) {
    //     return $this->createQueryBuilder('fml')
    //         ->andWhere('fml.followed = :followed')
    //         ->andWhere('fml.acceptedAt IS NOT NULL')
    //         ->setParameter('followed', $user)
    //         ->getQuery()
    //         ->getResult()
    //         ;
    // }
}
