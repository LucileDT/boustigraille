<?php

namespace App\Controller\API;

use App\Entity\NotificationReceipt;
use App\Repository\NotificationReceiptRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/api/notification')]
class APINotificationController extends AbstractController
{
    #[Route(path: '/has-unread', name: 'api_unread_notification', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function show(Request $request, NotificationReceiptRepository $notificationReceiptRepository): JsonResponse
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

    #[Route(path: '/toggle-read/{id}', name: 'api_notification_toggle_read', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function toggleRead(Request $request, NotificationReceipt $notificationReceipt): JsonResponse
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

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($notificationReceipt);
        $entityManager->flush();

        return new JsonResponse(['toggled']);
    }
}
