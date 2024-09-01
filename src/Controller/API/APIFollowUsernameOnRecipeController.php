<?php

namespace App\Controller\API;

use App\Entity\NotificationReceipt;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/follow-username-on-recipe', name: 'api_follow_username_on_recipe_')]
class APIFollowUsernameOnRecipeController extends AbstractController
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

        $followUsernameOnRecipe = $notificationReceipt->getNotification()->getAction();
        $now = new DateTimeImmutable();
        if ($followUsernameOnRecipe->getAcceptedAt() === null)
        {
            $followUsernameOnRecipe->setAcceptedAt($now);
            $followUsernameOnRecipe->setRefusedAt(null);
            $notificationReceipt->setProcessedAt($now);
        }
        else if ($followUsernameOnRecipe->getRefusedAt() === null)
        {
            $followUsernameOnRecipe->setRefusedAt($now);
            $followUsernameOnRecipe->setAcceptedAt(null);
            $notificationReceipt->setProcessedAt($now);
        }
        else
        {
            throw new Exception('A follow proposition can\'t be accepted and refused at the same time.');
        }

        $entityManager->persist($followUsernameOnRecipe);
        $entityManager->flush();

        return new JsonResponse(['toggled']);
    }

    #[Route(path: '/accept-follow/{id}', name: 'accept_follow', methods: ['POST'])]
    public function acceptFollow(EntityManagerInterface $entityManager, NotificationReceipt $notificationReceipt): JsonResponse
    {
        if (!$this->isAuthorized($notificationReceipt)) {
            return new JsonResponse(['not allowed']);
        }

        $followUsernameOnRecipe = $notificationReceipt->getNotification()->getAction();
        $now = new DateTimeImmutable();
        $followUsernameOnRecipe->setAcceptedAt($now);
        $followUsernameOnRecipe->setRefusedAt(null);
        $notificationReceipt->setProcessedAt($now);

        $entityManager->persist($followUsernameOnRecipe);
        $entityManager->flush();

        return new JsonResponse(['toggled']);
    }

    #[Route(path: '/refuse-follow/{id}', name: 'refuse_follow', methods: ['POST'])]
    public function refuseFollow(EntityManagerInterface $entityManager, NotificationReceipt $notificationReceipt): JsonResponse
    {
        if (!$this->isAuthorized($notificationReceipt)) {
            return new JsonResponse(['not allowed']);
        }

        $followUsernameOnRecipe = $notificationReceipt->getNotification()->getAction();
        $now = new DateTimeImmutable();
        $followUsernameOnRecipe->setRefusedAt($now);
        $followUsernameOnRecipe->setAcceptedAt(null);
        $notificationReceipt->setProcessedAt($now);

        $entityManager->persist($followUsernameOnRecipe);
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

        $followUsernameOnRecipe = $notificationReceipt->getNotification()->getAction();
        if ($connectedUser->getUsername() !== $followUsernameOnRecipe->getFollower()->getUsername())
        {
            $isAuthorized = false;
        }

        return $isAuthorized;
    }
}
