<?php

namespace App\Controller\API;

use App\Entity\FollowRequest;
use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/follow-request', name: 'api_follow_request_')]
class APIFollowRequestController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route(path: '/toggle-follow/{id}', name: 'toggle_follow', methods: ['POST'])]
    public function toggleFollow(FollowRequest $followRequest, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$this->isAuthorized($followRequest)) {
            return new JsonResponse(['not allowed'], 403);
        }

        $now = new \DateTimeImmutable();
        if ($followRequest->getAcceptedAt() === null)
        {
            $followRequest->setAcceptedAt($now);
            $followRequest->setRefusedAt(null);
            $followRequest->setProcessedAt($now);
        }
        else if ($followRequest->getRefusedAt() === null)
        {
            $followRequest->setRefusedAt($now);
            $followRequest->setAcceptedAt(null);
            $followRequest->setProcessedAt($now);
        }
        else
        {
            throw new Exception('A follow request can\'t be accepted and refused at the same time.');
        }

        $entityManager->persist($followRequest);
        $entityManager->flush();

        return new JsonResponse(['toggled']);
    }

    #[Route(path: '/accept-follow/{id}', name: 'accept_follow', methods: ['POST'])]
    public function acceptFollow(FollowRequest $followRequest, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$this->isAuthorized($followRequest)) {
            return new JsonResponse(['not allowed'], 403);
        }

        $now = new \DateTimeImmutable();
        $followRequest->setAcceptedAt($now);
        $followRequest->setRefusedAt(null);
        $followRequest->setProcessedAt($now);

        $entityManager->persist($followRequest);
        $entityManager->flush();

        return new JsonResponse(['accepted']);
    }

    #[Route(path: '/refuse-follow/{id}', name: 'refuse_follow', methods: ['POST'])]
    public function refuseFollow(FollowRequest $followRequest, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$this->isAuthorized($followRequest)) {
            return new JsonResponse(['not allowed'], 403);
        }

        $now = new \DateTimeImmutable();
        $followRequest->setRefusedAt($now);
        $followRequest->setAcceptedAt(null);
        $followRequest->setProcessedAt($now);

        $entityManager->persist($followRequest);
        $entityManager->flush();

        return new JsonResponse(['refused']);
    }

    private function isAuthorized(FollowRequest $followRequest): bool
    {
        $isAuthorized = true;
        /** @var \App\Entity\User $connectedUser */
        $connectedUser = $this->getUser();
        if (empty($connectedUser))
        {
            $isAuthorized = false;
        }

        if ($connectedUser->getUsername() !== $followRequest->getFollowed()->getUsername())
        {
            $isAuthorized = false;
        }

        return $isAuthorized;
    }
}
