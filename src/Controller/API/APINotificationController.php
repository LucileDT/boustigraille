<?php

namespace App\Controller\API;

use App\Entity\NotificationHistory;
use App\Repository\NotificationHistoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/api/notification', name: 'api_notification_')]
#[IsGranted('IS_AUTHENTICATED')]
class APINotificationController extends AbstractController
{
    #[Route(path: '/has-unread', name: 'unread', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function show(
        NotificationHistoryRepository $notificationHistoryRepository
    ): JsonResponse
    {
        $connectedUser = $this->getUser();
        $unreadNotificationHistory = $notificationHistoryRepository->findUnreadByUser($connectedUser);
        if (empty($unreadNotificationHistory)) {
            return new JsonResponse(['has_notification' => false, 'notifications_count' => 0]);
        } else {
            return new JsonResponse([
                'has_notification' => true,
                'notifications_count' => count($unreadNotificationHistory),
            ]);
        }
    }

    #[Route(path: '/toggle-read/{id}', name: 'toggle_read', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function toggleRead(
        NotificationHistory $notificationHistory,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $notificationReceived = $notificationHistory->getNotificationReceived();
        $connectedUser = $this->getUser();
        if ($notificationReceived->getRecipient() !== $connectedUser) {
            return new JsonResponse('unauthorized');
        }

        if (empty($notificationReceived->getReadAt())) {
            $notificationReceived->setReadAt(new \DateTimeImmutable());
        } else {
            $notificationReceived->setReadAt(null);
        }

        $entityManager->persist($notificationReceived);
        $entityManager->flush();

        return new JsonResponse(['toggled']);
    }
}
