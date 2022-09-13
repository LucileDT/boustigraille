<?php

namespace App\Controller;

use App\Entity\MealList;
use App\Form\GroceryListType;
use App\Form\MealListType;
use App\FormDataObject\GroceryListFDO;
use App\Repository\MealListRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/meal')]
class MealListController extends AbstractController
{
    #[Route('/list', name: 'meal_list_index', methods: ['GET'])]
    #[Security('not is_anonymous()')]
    public function index(MealListRepository $mealListRepository): Response
    {
        $user = $this->getUser();
        $groceryListFDO = new GroceryListFDO();
        $groceryListForm = $this->createForm(GroceryListType::class, $groceryListFDO, [
            'action' => $this->generateUrl('grocery_list_index'),
        ]);

        return $this->render('meal_list/index.html.twig', [
            'past_meal_lists' => $mealListRepository->findPastOnes($user),
            'current_meal_lists' => $mealListRepository->findCurrentOnes($user),
            'future_meal_lists' => $mealListRepository->findFutureOnes($user),
            'grocery_list_form' => $groceryListForm->createView(),
        ]);
    }

    #[Route('/new/{fromMealList}', name: 'meal_list_new', methods: ['GET', 'POST'])]
    #[Security('not is_anonymous()')]
    public function new(
        Request $request,
        MealListRepository $mealListRepository,
        MealList $fromMealList = null
    ): Response
    {
        $mealList = new MealList();
        if (!empty($fromMealList)) {
            $mealList->setStartDate($fromMealList->getStartDate());
            $mealList->setEndDate($fromMealList->getEndDate());
            $mealList->setIsStartingAtLunch($fromMealList->isStartingAtLunch());
            $mealList->setIsEndingAtLunch($fromMealList->isEndingAtLunch());
        }
        $form = $this->createForm(MealListType::class, $mealList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mealList->setAuthor($this->getUser());
            $this->updatDatesAccordingToStartingAndEndingTimes($mealList);
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
            $this->updatDatesAccordingToStartingAndEndingTimes($mealList);
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

    private function updatDatesAccordingToStartingAndEndingTimes(&$mealList) {
        if (!$mealList->isStartingAtLunch()) {
            $afternoonInterval = new \DateInterval("PT16H");
            $mealList->setStartDate(date_add($mealList->getStartDate(), $afternoonInterval));
        }
        if ($mealList->isEndingAtLunch()) {
            $afternoonInterval = new \DateInterval("PT16H");
            $mealList->setEndDate(date_add($mealList->getEndDate(), $afternoonInterval));
        } else {
            $eveningInterval = new \DateInterval("PT23H");
            $mealList->setEndDate(date_add($mealList->getEndDate(), $eveningInterval));
        }
    }
}
