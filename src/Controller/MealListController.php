<?php

namespace App\Controller;

use App\Entity\MealList;
use App\Form\MealListType;
use App\Repository\MealListRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/meal/list')]
class MealListController extends AbstractController
{
    #[Route('/', name: 'meal_list_index', methods: ['GET'])]
    public function index(MealListRepository $mealListRepository): Response
    {
        return $this->render('meal_list/index.html.twig', [
            'meal_lists' => $mealListRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'meal_list_new', methods: ['GET', 'POST'])]
    #[Security('not is_anonymous()')]
    public function new(Request $request, MealListRepository $mealListRepository): Response
    {
        $mealList = new MealList();
        $form = $this->createForm(MealListType::class, $mealList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mealListRepository->add($mealList);

            return $this->redirectToRoute('meal_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('meal_list/new.html.twig', [
            'meal_list' => $mealList,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'meal_list_show', methods: ['GET'])]
    public function show(MealList $mealList): Response
    {
        return $this->render('meal_list/show.html.twig', [
            'meal_list' => $mealList,
        ]);
    }

    #[Route('/{id}/edit', name: 'meal_list_edit', methods: ['GET', 'POST'])]
    #[Security('not is_anonymous()')]
    public function edit(Request $request, MealList $mealList, MealListRepository $mealListRepository): Response
    {
        $form = $this->createForm(MealListType::class, $mealList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mealListRepository->add($mealList);
            return $this->redirectToRoute('meal_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('meal_list/edit.html.twig', [
            'meal_list' => $mealList,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'meal_list_delete', methods: ['POST'])]
    #[Security('not is_anonymous()')]
    public function delete(Request $request, MealList $mealList, MealListRepository $mealListRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mealList->getId(), $request->request->get('_token'))) {
            $mealListRepository->remove($mealList);
        }

        return $this->redirectToRoute('meal_list_index', [], Response::HTTP_SEE_OTHER);
    }
}
