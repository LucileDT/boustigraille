<?php

namespace App\Controller\API;

use App\Entity\NotificationReceipt;
use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/follow-meal-list', name: 'api_follow_meal_list_')]
class APIFollowMealListController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route(path: '/toggle-follow/{id}', name: 'toggle_follow', methods: ['POST'])]
    public function toggleFollow(EntityManagerInterface $entityManager, NotificationReceipt $notificationReceipt): JsonResponse
    {
        if (!$this->isAuthorized($notificationReceipt)) {
            return new JsonResponse(['not allowed']);
        }

        $followMealList = $notificationReceipt->getNotification()->getAction();
        $now = new \DateTimeImmutable();
        if ($followMealList->getAcceptedAt() === null)
        {
            $followMealList->setAcceptedAt($now);
            $followMealList->setRefusedAt(null);
            $notificationReceipt->setProcessedAt($now);
        }
        else if ($followMealList->getRefusedAt() === null)
        {
            $followMealList->setRefusedAt($now);
            $followMealList->setAcceptedAt(null);
            $notificationReceipt->setProcessedAt($now);
        }
        else
        {
            throw new Exception('A follow proposition can\'t be accepted and refused at the same time.');
        }

        $entityManager->persist($followMealList);
        $entityManager->flush();

        return new JsonResponse(['toggled']);
    }

    #[Route(path: '/accept-follow/{id}', name: 'accept_follow', methods: ['POST'])]
    public function acceptFollow(EntityManagerInterface $entityManager,  NotificationReceipt $notificationReceipt): JsonResponse
    {
        if (!$this->isAuthorized($notificationReceipt)) {
            return new JsonResponse(['not allowed']);
        }

        $followMealList = $notificationReceipt->getNotification()->getAction();
        $now = new \DateTimeImmutable();
        $followMealList->setAcceptedAt($now);
        $followMealList->setRefusedAt(null);
        $notificationReceipt->setProcessedAt($now);

        $entityManager->persist($followMealList);
        $entityManager->flush();

        return new JsonResponse(['toggled']);
    }

    #[Route(path: '/refuse-follow/{id}', name: 'refuse_follow', methods: ['POST'])]
    public function refuseFollow(EntityManagerInterface $entityManager, NotificationReceipt $notificationReceipt): JsonResponse
    {
        if (!$this->isAuthorized($notificationReceipt)) {
            return new JsonResponse(['not allowed']);
        }

        $followMealList = $notificationReceipt->getNotification()->getAction();
        $now = new \DateTimeImmutable();
        $followMealList->setRefusedAt($now);
        $followMealList->setAcceptedAt(null);
        $notificationReceipt->setProcessedAt($now);

        $entityManager->persist($followMealList);
        $entityManager->flush();

        return new JsonResponse(['toggled']);
    }

    private function isAuthorized(NotificationReceipt $notificationReceipt): bool
    {
        $isAuthorized = true;
        /** @var \App\Entity\User $connectedUser */
        $connectedUser = $this->getUser();
        if (empty($connectedUser))
        {
            $isAuthorized = false;
        }

        if ($connectedUser->getUsername() !== $notificationReceipt->getRecipient()->getUsername())
        {
            $isAuthorized = false;
        }

        $followMealList = $notificationReceipt->getNotification()->getAction();
        if ($connectedUser->getUsername() !== $followMealList->getFollower()->getUsername())
        {
            $isAuthorized = false;
        }

        return $isAuthorized;
    }
}
