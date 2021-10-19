<?php

namespace App\Controller\API;

use App\Entity\Recipe;
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
    public function show(Request $request, Recipe $recipe): JsonResponse
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
}
