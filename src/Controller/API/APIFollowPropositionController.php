<?php

namespace App\Controller\API;

use App\Entity\FollowProposition;
use App\Service\NotificationService;
use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/follow-proposition', name: 'api_follow_proposition_')]
class APIFollowPropositionController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route(path: '/toggle-follow/{id}', name: 'toggle_follow', methods: ['POST'])]
    public function toggleFollow(FollowProposition $followProposition, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$this->isAuthorized($followProposition)) {
            return new JsonResponse(['not allowed'], 403);
        }

        $now = new \DateTimeImmutable();
        if ($followProposition->getAcceptedAt() === null)
        {
            $followProposition->setAcceptedAt($now);
            $followProposition->setRefusedAt(null);
            $followProposition->setProcessedAt($now);
        }
        else if ($followProposition->getRefusedAt() === null)
        {
            $followProposition->setRefusedAt($now);
            $followProposition->setAcceptedAt(null);
            $followProposition->setProcessedAt($now);
        }
        else
        {
            throw new Exception('A follow proposition can\'t be accepted and refused at the same time.');
        }

        $entityManager->persist($followProposition);
        $entityManager->flush();

        return new JsonResponse(['toggled']);
    }

    #[Route(path: '/accept-follow/{id}', name: 'accept_follow', methods: ['POST'])]
    public function acceptFollow(
        FollowProposition $followProposition,
        EntityManagerInterface $entityManager,
        NotificationService $notificationService
    ): JsonResponse
    {
        if (!$this->isAuthorized($followProposition)) {
            return new JsonResponse(['not allowed'], 403);
        }

        $now = new \DateTimeImmutable();
        $followProposition->setAcceptedAt($now);
        $followProposition->setRefusedAt(null);
        $followProposition->setProcessedAt($now);

        $entityManager->persist($followProposition);
        $entityManager->flush();

        $notification = new Notification(
            $followProposition->getType()->getLabel(),
            ['browser']
        );

        $notification->content(
            '**' . $followProposition->getFollower()->getUsername() . '**' .
            ' a accepté votre demande de suivi !'
        );

        $notificationService->sendNotification(
            $notification,
            $followProposition->getFollowed(),
            $followProposition->getFollower(),
        );

        return new JsonResponse(['accepted']);
    }

    #[Route(path: '/refuse-follow/{id}', name: 'refuse_follow', methods: ['POST'])]
    public function refuseFollow(
        FollowProposition $followProposition,
        EntityManagerInterface $entityManager,
        NotificationService $notificationService
    ): JsonResponse
    {
        if (!$this->isAuthorized($followProposition)) {
            return new JsonResponse(['not allowed'], 403);
        }

        $isRevoked = $followProposition->getAcceptedAt() !== null;

        $now = new \DateTimeImmutable();
        $followProposition->setRefusedAt($now);
        $followProposition->setAcceptedAt(null);
        $followProposition->setProcessedAt($now);

        $entityManager->persist($followProposition);
        $entityManager->flush();

        $notification = new Notification(
            $followProposition->getType()->getLabel(),
            ['browser']
        );

        $notificationContent = '**' . $followProposition->getFollower()->getUsername() . '** a ';
        $notificationContent .= $isRevoked ? 'révoqué' : 'refusé';
        $notificationContent .=' votre demande de suivi.';

        $notification->content($notificationContent);

        $notificationService->sendNotification(
            $notification,
            $followProposition->getFollowed(),
            $followProposition->getFollower(),
        );

        return new JsonResponse(['refused']);
    }

    private function isAuthorized(FollowProposition $followProposition): bool
    {
        $isAuthorized = true;
        /** @var \App\Entity\User $connectedUser */
        $connectedUser = $this->getUser();
        if (empty($connectedUser))
        {
            $isAuthorized = false;
        }

        if ($connectedUser->getUsername() !== $followProposition->getFollower()->getUsername())
        {
            $isAuthorized = false;
        }

        return $isAuthorized;
    }
}
