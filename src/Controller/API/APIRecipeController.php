<?php

namespace App\Controller\API;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/recipe', name: 'api_recipe_')]
class APIRecipeController extends AbstractController
{
    #[Route(path: '/toggle-favorite/{id}', name: 'toggle_favorite', methods: ['POST'])]
    public function toggleFavorite(EntityManagerInterface $entityManager, Recipe $recipe): JsonResponse
    {
        /** @var \App\Entity\User $connectedUser */
        $connectedUser = $this->getUser();
        if (empty($connectedUser))
        {
            return new JsonResponse(['not allowed']);
        }

        if ($connectedUser->hasFaved($recipe)) {
            $connectedUser->removeFavoriteRecipe($recipe);
        } else {
            $connectedUser->addFavoriteRecipe($recipe);
        }

        $entityManager->persist($connectedUser);
        $entityManager->persist($recipe);
        $entityManager->flush();

        return new JsonResponse(['toggled']);
    }

    #[Route(path: '/recipe/by-favorite', name: 'by_favorite', methods: ['GET'])]
    public function getRecipesGroupedByFavorite(Request $request, RecipeRepository $recipeRepository, Security $security): JsonResponse
    {
        /** @var \App\Entity\User $connectedUser */
        $connectedUser = $this->getUser();
        if (empty($connectedUser))
        {
            return new JsonResponse(['not allowed']);
        }

        $name = $request->query->get('q');
        $nameWithoutSpecialCharacters = transliterator_transliterate('Any-Latin; Latin-ASCII; [\u0080-\u7fff] remove', $name);
        $favedRecipes = $recipeRepository->findByFavedByAndTransliteratedName($connectedUser, $nameWithoutSpecialCharacters);
        $notFavedRecipes = $recipeRepository->findByNotFavedByAndTransliteratedName($connectedUser, $nameWithoutSpecialCharacters);

        foreach ($favedRecipes as &$favedRecipe) {
            $favedRecipe->canViewAuthorUsername = $security->isGranted('view_author_username', $favedRecipe);
        }

        foreach ($notFavedRecipes as &$notFavedRecipe) {
            $notFavedRecipe->canViewAuthorUsername = $security->isGranted('view_author_username', $notFavedRecipe);
        }

        $groupedData = [
            'faved' => $favedRecipes,
            'not_faved' => $notFavedRecipes,
        ];

        return new JsonResponse($groupedData);
    }

    #[Route(path: '/suggested', name: 'suggested', methods: ['GET'])]
    public function getSuggestedRecipes(Request $request, RecipeRepository $recipeRepository, Security $security): JsonResponse
    {
        /** @var \App\Entity\User $connectedUser */
        $connectedUser = $this->getUser();
        if (empty($connectedUser))
        {
            return new JsonResponse(['not allowed']);
        }

        $otherSuggestedRecipes = [];
        $firstSuggestedRecipes = $recipeRepository->findFavoritedRecipesNeverMade($connectedUser);
        if (count($firstSuggestedRecipes) !== 6) {
            $otherSuggestedRecipes = $recipeRepository->findFavoritedRecipesOldestMade($connectedUser, 6 - count($firstSuggestedRecipes));
        }
        $suggestedRecipes = array_merge($firstSuggestedRecipes, $otherSuggestedRecipes);
        foreach ($suggestedRecipes as &$suggestedRecipe) {
            $suggestedRecipe->canViewAuthorUsername = $security->isGranted('view_author_username', $suggestedRecipe);
        }
        return new JsonResponse($suggestedRecipes);
    }
}
