<?php

namespace App\Repository;

use App\Entity\NotificationHistory;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NotificationHistory>
 *
 * @method NotificationHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotificationHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotificationHistory[]    findAll()
 * @method NotificationHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationHistory::class);
    }

    /**
     * @param User $user The user receiving the notification.
     *
     * @return QueryBuilder A query builder object containing the base query for finding a NotificationHistory by User.
     */
    private function getFindByUserBaseQuery(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('nh')
            ->andWhere('nr.recipient = :user')
            ->join('nh.notificationReceived', 'nr')
            ->join('nh.notificationSent', 'ns')
            ->orderBy('ns.sentAt', 'DESC')
            ->setParameter('user', $user)
        ;
    }

    /**
     * @param User $user The user receiving the notification.
     *
     * @return NotificationHistory[] Returns an array of NotificationHistory objects
     */
    public function findUnreadByUser(User $user): array
    {
        $baseQuery = $this->getFindByUserBaseQuery($user);

        return $baseQuery->andWhere('nr.readAt IS NULL')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param User $user The user receiving the notification.
     *
     * @return NotificationHistory[] Returns an array of NotificationHistory objects
     */
    public function findReadByUser(User $user): array
    {
        $baseQuery = $this->getFindByUserBaseQuery($user);

        return $baseQuery->andWhere('nr.readAt IS NOT NULL')
            ->getQuery()
            ->getResult()
        ;
    }
}
