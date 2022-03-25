<?php

namespace App\Service\Migrations;

use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function sendBoustigrailleUpdateNotificationToEveryone($message)
    {
        $connection = $this->entityManager->getConnection();

        $stmt = $connection->prepare('SELECT * FROM notification_category WHERE code = 1');
        $result = $stmt->executeQuery();
        $notificationCategories = $result->fetchAllAssociative();
        $notificationCategoryId = $notificationCategories[0]['id'];

        $stmt = $connection->prepare('INSERT INTO notification (id, message, date_sent, category_id) VALUES (nextval(\'notification_id_seq\'), ?, NOW(), ?)');
        $stmt->bindValue(1, $message);
        $stmt->bindValue(2, $notificationCategoryId);
        $stmt->executeQuery();
        $notificationId = $connection->lastInsertId('notification_id_seq');

        $stmt = $connection->prepare('SELECT * FROM "user"');
        $result = $stmt->executeQuery();
        $users = $result->fetchAllAssociative();
        foreach ($users as $user) {
            $stmt = $connection->prepare('INSERT INTO notification_receipt (id, recipient_id, notification_id) VALUES (nextval(\'notification_id_seq\'), ?, ?)');
            $stmt->bindValue(1, $user['id']);
            $stmt->bindValue(2, $notificationId);
            $stmt->executeQuery();
        }
    }

    public function removeBoustigrailleUpdateNotification($message)
    {
        $connection = $this->entityManager->getConnection();
        $stmt = $connection->prepare('SELECT * FROM notification WHERE message = ?');
        $stmt->bindValue(1, $message);
        $result = $stmt->executeQuery();
        $notifications = $result->fetchAllAssociative();
        $notificationId = $notifications[0]['id'];

        $stmt = $connection->prepare('DELETE FROM notification_receipt WHERE notification_id = ?');
        $stmt->bindValue(1, $notificationId);
        $stmt->executeQuery();

        $stmt = $connection->prepare('DELETE FROM notification WHERE id = ?');
        $stmt->bindValue(1, $notificationId);
        $stmt->executeQuery();
    }

}
