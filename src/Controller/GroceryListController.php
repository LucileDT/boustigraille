<?php

namespace App\Controller;

use App\Form\GroceryListType;
use App\FormDataObject\GroceryListFDO;
use App\Service\GroceryListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/grocery/list', name: 'grocery_list_')]
class GroceryListController extends AbstractController
{
    #[Route(path: '/', name: 'index', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function index(Request $request): Response
    {
        $groceryListFDO = new GroceryListFDO();
        $groceryListForm = $this->createForm(GroceryListType::class, $groceryListFDO);
        $groceryListForm->handleRequest($request);

        if ($groceryListForm->isSubmitted() && $groceryListForm->isValid()) {
            $mealLists = $groceryListFDO->getMealLists();
            $groceryList = GroceryListService::generateFormattedGroceryList($mealLists);

            return $this->render('grocery_list/index.html.twig', [
                'grocery_list' => $groceryList,
                'meal_lists' => $mealLists,
            ]);
        }

        return $this->redirectToRoute('meal_list_index');
    }
}
