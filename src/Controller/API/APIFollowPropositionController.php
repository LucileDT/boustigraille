<?php

namespace App\Controller\API;

use App\Entity\FollowProposition;
use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function acceptFollow(FollowProposition $followProposition, EntityManagerInterface $entityManager): JsonResponse
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

        return new JsonResponse(['accepted']);
    }

    #[Route(path: '/refuse-follow/{id}', name: 'refuse_follow', methods: ['POST'])]
    public function refuseFollow(FollowProposition $followProposition, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$this->isAuthorized($followProposition)) {
            return new JsonResponse(['not allowed'], 403);
        }

        $now = new \DateTimeImmutable();
        $followProposition->setRefusedAt($now);
        $followProposition->setAcceptedAt(null);
        $followProposition->setProcessedAt($now);

        $entityManager->persist($followProposition);
        $entityManager->flush();

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

        if ($connectedUser->getUsername() !== $followProposition->getFollowed()->getUsername())
        {
            $isAuthorized = false;
        }

        return $isAuthorized;
    }
}
