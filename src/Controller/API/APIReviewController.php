<?php

namespace App\Controller\API;

use App\Entity\Recipe;
use App\Entity\Review;
use App\Entity\User;
use App\Repository\RecipeRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/review', name: 'api_review_')]
class APIReviewController extends AbstractController
{
    #[Route(path: '/rate/{id}', name: 'rate_recipe', methods: ['POST'])]
    public function rateRecipe(
        Request $request,
        EntityManagerInterface $entityManager,
        ReviewRepository $reviewRepository,
        Recipe $recipe
    ): JsonResponse
    {
        /** @var User $connectedUser */
        $connectedUser = $this->getUser();
        if (empty($connectedUser))
        {
            return new JsonResponse(['not allowed']);
        }

        $grade = $request->getPayload()->get('review-grade');
        $review = $reviewRepository->findOneBy([
            'author' => $connectedUser->getId(),
            'recipe' => $recipe->getId(),
        ]);
        if (empty($review)) {
            $review = new Review();
            $review->setAuthor($connectedUser);
            $review->setRecipe($recipe);
        }
        $review->setGrade($grade);
        $entityManager->persist($review);
        $entityManager->flush();

        return new JsonResponse(['done']);
    }
}
