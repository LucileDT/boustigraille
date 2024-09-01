<?php

namespace App\Controller;

use App\Form\IngredientFromOpenFoodFactsType;
use App\FormDataObject\IngredientFromOpenFoodFactsFDO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Home controller
 * It manages the home page (shown when logged in)
 */
class HomeController extends AbstractController
{
    #[Route(path: '/', name: 'home', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function index(): Response
    {
        $ingredientFromOpenFoodFactsFDO = new IngredientFromOpenFoodFactsFDO();
        $formNewIngredient = $this->createForm(
            IngredientFromOpenFoodFactsType::class,
            $ingredientFromOpenFoodFactsFDO,
            [
                'action' => $this->generateUrl('ingredient_new_from_openfoodfacts'),
            ]
        );

        return $this->render('home/home.html.twig', [
            'formFromOpenFoodFacts' => $formNewIngredient->createView(),
        ]);
    }
}
