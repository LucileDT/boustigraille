<?php

namespace App\Service;

use App\Entity\NotificationHistory;
use App\Entity\NotificationReceived;
use App\Entity\NotificationSent;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;

/**
 * Class containing methods used to send notifications from Boustigraille
 */
class NotificationService
{
    public function __construct(
        private NotifierInterface $notifier,
        private EntityManagerInterface $entityManager
    ) { }

    public function sendNotification(Notification $notification, User $recipient, User $sender = null) {
        // Create notification history
        $notificationHistory = new NotificationHistory();
        $notificationHistory->hydrateFromNotificationAndRecipient($notification, $recipient);
        $this->entityManager->persist($notificationHistory);

        // Create notification sent joint table
        $notificationSent = new NotificationSent();
        $notificationSent->setSentAt(new DateTimeImmutable());
        if ($sender != null) {
            $notificationSent->setSender($sender);
        }
        $notificationHistory->setNotificationSent($notificationSent);
        $this->entityManager->persist($notificationSent);

        // Create notification received joint table
        $notificationReceived = new NotificationReceived();
        $notificationReceived->setRecipient($recipient);
        $notificationHistory->setNotificationReceived($notificationReceived);
        $this->entityManager->persist($notificationReceived);

        // Send the notification
        $this->notifier->send($notification, $recipient);

        // Save the entities in DB
        $this->entityManager->flush();
    }
}
