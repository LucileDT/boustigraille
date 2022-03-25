<?php

namespace App\Controller\API;

use App\Entity\Recipe;
use App\Repository\NotificationReceiptRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/notification")
 */
class APINotificationController extends AbstractController
{
    /**
     * @Route("/has-unread", name="api_unread_notification", methods={"GET"})
     * @Security("not is_anonymous()")
     */
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
}
