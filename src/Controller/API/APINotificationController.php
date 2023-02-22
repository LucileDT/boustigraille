<?php

namespace App\Controller\API;

use App\Entity\NotificationReceipt;
use App\Repository\NotificationReceiptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/notification', name: 'api_notification_')]
#[Security('is_authenticated()')]
class APINotificationController extends AbstractController
{
    #[Route('/has-unread', name: 'unread', methods: ['GET'])]
    public function show(NotificationReceiptRepository $notificationReceiptRepository): JsonResponse
    {
        $connectedUser = $this->getUser();
        $unreadNotificationReceipts = $notificationReceiptRepository->findUnreadByUser($connectedUser);
        if (empty($unreadNotificationReceipts)) {
            return new JsonResponse(['has_notification' => false, 'notifications_count' => 0]);
        } else {
            return new JsonResponse([
                'has_notification' => true,
                'notifications_count' => count($unreadNotificationReceipts),
            ]);
        }
    }

    #[Route('/toggle-read/{id}', name: 'toggle_read', methods: ['POST'])]
    public function toggleRead(EntityManagerInterface $entityManager, NotificationReceipt $notificationReceipt): JsonResponse
    {
        $connectedUser = $this->getUser();
        if ($notificationReceipt->getRecipient() !== $connectedUser) {
            return new JsonResponse('unauthorized');
        }

        if (empty($notificationReceipt->getDateRead())) {
            $notificationReceipt->setDateRead(new \DateTime());
        } else {
            $notificationReceipt->setDateRead(null);
        }

        $entityManager->persist($notificationReceipt);
        $entityManager->flush();

        return new JsonResponse(['toggled']);
    }
}
