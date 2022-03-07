<?php

namespace App\Controller;

use App\Form\GroceryListType;
use App\FormDataObject\GroceryListFDO;
use App\Service\GroceryListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/grocery/list')]
class GroceryListController extends AbstractController
{
    #[Route('/', name: 'grocery_list_index', methods: ['POST'])]
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
