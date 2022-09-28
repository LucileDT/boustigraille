<?php

namespace App\Controller\API;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/recipe")
 */
class APIRecipeController extends AbstractController
{
    /**
     * @Route("/toggle-favorite/{id}", name="api_recipe_toggle_favorite", methods={"POST"})
     */
    public function toggleFavorite(Request $request, Recipe $recipe): JsonResponse
    {
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
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($connectedUser);
        $entityManager->persist($recipe);
        $entityManager->flush();

        return new JsonResponse(['toggled']);
    }

    /**
     * @Route("/recipe/by-favourite", name="api_recipes_by_favorite", methods={"GET"})
     */
    public function getRecipesGroupedByFavorite(Request $request, RecipeRepository $recipeRepository): JsonResponse
    {
        $connectedUser = $this->getUser();
        if (empty($connectedUser))
        {
            return new JsonResponse(['not allowed']);
        }

        $name = $request->query->get('q');
        $favedRecipes = $recipeRepository->findByFavedByAndName($connectedUser, $name);
        $notFavedRecipes = $recipeRepository->findByNotFavedByAndName($connectedUser, $name);

        $groupedData = [
            'faved' => $favedRecipes,
            'not_faved' => $notFavedRecipes,
        ];

        return new JsonResponse($groupedData);
    }

    /**
     * @Route("/suggested", name="api_suggested_recipes", methods={"GET"})
     */
    public function getSuggestedRecipes(Request $request, RecipeRepository $recipeRepository): JsonResponse
    {
        $connectedUser = $this->getUser();
        if (empty($connectedUser))
        {
            return new JsonResponse(['not allowed']);
        }

        $suggestedRecipes = [];
        $firstSuggestedRecipes = $recipeRepository->findFavoritedRecipesNeverMade($this->getUser());
        if (count($firstSuggestedRecipes) !== 6) {
            $otherSuggestedRecipes = $recipeRepository->findFavoritedRecipesOldestMade($this->getUser(), 6 - count($firstSuggestedRecipes));
            $suggestedRecipes = array_merge($firstSuggestedRecipes, $otherSuggestedRecipes);
        }
        return new JsonResponse($suggestedRecipes);
    }
}
