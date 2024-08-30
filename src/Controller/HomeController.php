<?php
/**
 * Home controller
 */

namespace App\Controller;

use App\Form\IngredientFromOpenFoodFactsType;
use App\FormDataObject\IngredientFromOpenFoodFactsFDO;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Home controller
 */
class HomeController extends AbstractController
{
    /**
     * @param Request $request The request.
     * @return Response
     */
    #[Route(path: '/', name: 'home')]
    #[Security('not is_anonymous()')]
    public function indexAction()
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
