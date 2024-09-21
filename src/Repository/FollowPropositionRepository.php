<?php

namespace App\Repository;

use App\Entity\FollowProposition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FollowProposition>
 *
 * @method FollowProposition|null find($id, $lockMode = null, $lockVersion = null)
 * @method FollowProposition|null findOneBy(array $criteria, array $orderBy = null)
 * @method FollowProposition[]    findAll()
 * @method FollowProposition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FollowPropositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FollowProposition::class);
    }

    /**
     * @return FollowProposition[]
     */
    public function findUserOnesUnprocessed($user) {
        return $this->createQueryBuilder('fr')
            ->andWhere('fr.follower = :follower')
            ->andWhere('fr.processedAt IS NULL')
            ->setParameter('follower', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return FollowProposition[]
     */
    public function findUserOnesAccepted($user) {
        return $this->createQueryBuilder('fr')
            ->andWhere('fr.follower = :follower')
            ->andWhere('fr.acceptedAt IS NOT NULL')
            ->setParameter('follower', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return FollowProposition[]
     */
    public function findUserOnesRefused($user) {
        return $this->createQueryBuilder('fr')
            ->andWhere('fr.follower = :follower')
            ->andWhere('fr.refusedAt IS NOT NULL')
            ->setParameter('follower', $user)
            ->getQuery()
            ->getResult()
            ;
    }
}
