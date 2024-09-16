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

    /**
     * @return FollowRequest[]
     */
    public function findUserOnesUnprocessed($user) {
        return $this->createQueryBuilder('fr')
            ->andWhere('fr.followed = :followed')
            ->andWhere('fr.processedAt IS NULL')
            ->setParameter('followed', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return FollowRequest[]
     */
    public function findUserOnesAccepted($user) {
        return $this->createQueryBuilder('fr')
            ->andWhere('fr.followed = :followed')
            ->andWhere('fr.acceptedAt IS NOT NULL')
            ->setParameter('followed', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return FollowRequest[]
     */
    public function findUserOnesRefused($user) {
        return $this->createQueryBuilder('fr')
            ->andWhere('fr.followed = :followed')
            ->andWhere('fr.refusedAt IS NOT NULL')
            ->setParameter('followed', $user)
            ->getQuery()
            ->getResult()
            ;
    }
}
