<?php

namespace App\Repository;

use App\Entity\FollowProposition;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    private function getFindFollowPropositionBaseQuery(
        User $follower,
        User $followed,
        string $followTypeCode
    ): QueryBuilder
    {
        return $this->createQueryBuilder('fp')
            ->leftJoin('fp.type', 'ft')
            ->andWhere('fp.follower = :follower')
            ->andWhere('fp.followed = :followed')
            ->andWhere('ft.code = :followTypeCode')
            ->setParameter('follower', $follower)
            ->setParameter('followed', $followed)
            ->setParameter('followTypeCode', $followTypeCode)
        ;
    }

    /**
     * @return FollowProposition
     */
    public function findFollowProposition(
        User $follower,
        User $followed,
        string $followTypeCode
    ): mixed
    {
        return $this->getFindFollowPropositionBaseQuery(
            $follower,
            $followed,
            $followTypeCode
        )
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return FollowProposition[]
     */
    public function findByFollowedAndType(
        User $followed,
        string $followTypeCode
    ): mixed
    {
        return $this->createQueryBuilder('fp')
            ->leftJoin('fp.type', 'ft')
            ->andWhere('fp.followed = :followed')
            ->andWhere('ft.code = :followTypeCode')
            ->setParameter('followed', $followed)
            ->setParameter('followTypeCode', $followTypeCode)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return FollowProposition[]
     */
    public function findUserOnesUnprocessed(User $user): mixed
    {
        return $this->createQueryBuilder('fp')
            ->andWhere('fp.follower = :follower')
            ->andWhere('fp.processedAt IS NULL')
            ->setParameter('follower', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return FollowProposition[]
     */
    public function findUserOnesAccepted(User $user, ?string $followTypeCode = null): mixed
    {
        $queryBuilder = $this->createQueryBuilder('fp')
            ->andWhere('fp.follower = :follower')
            ->andWhere('fp.acceptedAt IS NOT NULL')
            ->setParameter('follower', $user);

        if (!is_null($followTypeCode)) {
            $queryBuilder
                ->leftJoin('fp.type', 'ft')
                ->andWhere('ft.code = :followTypeCode')
                ->setParameter('followTypeCode', $followTypeCode)
            ;
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return FollowProposition[]
     */
    public function findUserOnesRefused(User $user): mixed
    {
        return $this->createQueryBuilder('fp')
            ->andWhere('fp.follower = :follower')
            ->andWhere('fp.refusedAt IS NOT NULL')
            ->setParameter('follower', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return FollowProposition[]
     */
    public function findFollowedOnesAccepted(User $followed, ?string $followTypeCode = null): mixed
    {
        $queryBuilder = $this->createQueryBuilder('fp')
            ->andWhere('fp.followed = :followed')
            ->andWhere('fp.acceptedAt IS NOT NULL')
            ->setParameter('followed', $followed);

        if (!is_null($followTypeCode)) {
            $queryBuilder
                ->leftJoin('fp.type', 'ft')
                ->andWhere('ft.code = :followTypeCode')
                ->setParameter('followTypeCode', $followTypeCode)
            ;
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function hasAcceptedFollowPropositionFromUser(
        User $follower,
        User $followed,
        string $followTypeCode
    ): bool
    {
        $results = $this->getFindFollowPropositionBaseQuery(
            $follower,
            $followed,
            $followTypeCode
        )
            ->andWhere('fp.acceptedAt IS NOT NULL')
            ->getQuery()
            ->getResult()
        ;

        return count($results) > 0;
    }
}
