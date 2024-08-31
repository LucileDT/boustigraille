<?php

namespace App\Controller;

use App\Form\IngredientFromOpenFoodFactsType;
use App\FormDataObject\IngredientFromOpenFoodFactsFDO;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Home controller
 * It manages the home page (shown when logged in)
 */
class HomeController extends AbstractController
{
    #[Route(path: '/', name: 'home')]
    #[IsGranted('IS_AUTHENTICATED')]
    public function indexAction(): Response
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
