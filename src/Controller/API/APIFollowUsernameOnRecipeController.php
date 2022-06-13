<?php

namespace App\Controller\API;

use App\Entity\NotificationReceipt;
use DateTimeImmutable;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/follow-username-on-recipe")
 */
class APIFollowUsernameOnRecipeController extends AbstractController
{
    /**
     * @Route("/toggle-follow/{id}", name="api_follow_username_on_recipe_toggle_follow", methods={"POST"})
     * @throws Exception
     */
    public function toggleFollow(Request $request, NotificationReceipt $notificationReceipt): JsonResponse
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
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($followUsernameOnRecipe);
        $entityManager->flush();

        return new JsonResponse(['toggled']);
    }

    /**
     * @Route("/accept-follow/{id}", name="api_follow_username_on_recipe_accept_follow", methods={"POST"})
     */
    public function acceptFollow(Request $request, NotificationReceipt $notificationReceipt): JsonResponse
    {
        if (!$this->isAuthorized($notificationReceipt)) {
            return new JsonResponse(['not allowed']);
        }

        $followUsernameOnRecipe = $notificationReceipt->getNotification()->getAction();
        $now = new DateTimeImmutable();
        $followUsernameOnRecipe->setAcceptedAt($now);
        $followUsernameOnRecipe->setRefusedAt(null);
        $notificationReceipt->setProcessedAt($now);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($followUsernameOnRecipe);
        $entityManager->flush();

        return new JsonResponse(['toggled']);
    }

    /**
     * @Route("/refuse-follow/{id}", name="api_follow_username_on_recipe_refuse_follow", methods={"POST"})
     */
    public function refuseFollow(Request $request, NotificationReceipt $notificationReceipt): JsonResponse
    {
        if (!$this->isAuthorized($notificationReceipt)) {
            return new JsonResponse(['not allowed']);
        }

        $followUsernameOnRecipe = $notificationReceipt->getNotification()->getAction();
        $now = new DateTimeImmutable();
        $followUsernameOnRecipe->setRefusedAt($now);
        $followUsernameOnRecipe->setAcceptedAt(null);
        $notificationReceipt->setProcessedAt($now);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($followUsernameOnRecipe);
        $entityManager->flush();

        return new JsonResponse(['toggled']);
    }

    private function isAuthorized(NotificationReceipt $notificationReceipt): bool
    {
        $isAuthorized = true;
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
