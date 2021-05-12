<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientFromOpenFoodFactsType;
use App\Form\IngredientType;
use App\FormDataObject\IngredientFromOpenFoodFactsFDO;
use App\Repository\IngredientRepository;
use App\Service\OpenFoodFactService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ingredient")
 */
class IngredientController extends AbstractController
{
    /**
     * @Route("/", name="ingredient_index", methods={"GET"})
     */
    public function index(IngredientRepository $ingredientRepository): Response
    {
        $ingredientFromOpenFoodFactsFDO = new IngredientFromOpenFoodFactsFDO();
        $form = $this->createForm(IngredientFromOpenFoodFactsType::class, $ingredientFromOpenFoodFactsFDO, [
            'action' => $this->generateUrl('ingredient_new_from_openfoodfacts'),
        ]);

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->findBy([], ['label' => 'ASC']),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="ingredient_new", methods={"GET","POST"})
     * @Security("not is_anonymous()")
     */
    public function new(Request $request): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ingredient);
            $entityManager->flush();

            return $this->redirectToRoute('ingredient_index');
        }

        return $this->render('ingredient/new.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new-from-openfoodfacts", name="ingredient_new_from_openfoodfacts", methods={"GET","POST"})
     * @Security("not is_anonymous()")
     */
    public function newFromOpenFoodFacts(OpenFoodFactService $offService, Request $request, IngredientFromOpenFoodFactsFDO $ingredientIdentifier): Response
    {
        $form = $this->createForm(IngredientFromOpenFoodFactsType::class, $ingredientIdentifier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productBarCode = '';
            if (filter_var($ingredientIdentifier->getIdentifier(), FILTER_VALIDATE_URL) !== false)
            {
                $productUrl = $ingredientIdentifier->getIdentifier();
                $productBarCode = $offService->getBarCodeFromProductUrl($productUrl);
            }
            else
            {
                $productBarCode = $ingredientIdentifier->getIdentifier();
            }

            $product = $offService->getProductFromApi($productBarCode);
            $ingredient = new Ingredient();

            try
            {
                $offService->fillIngredientNutritionalDataWithProductOnes($ingredient, $product);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ingredient);
                $entityManager->flush();
            }
            catch (\Exception $ex)
            {
                $this->addFlash('danger', $ex->getMessage());
            }


            return $this->redirectToRoute('ingredient_index');
        }

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="ingredient_show", methods={"GET"})
     */
    public function show(Ingredient $ingredient): Response
    {
        return $this->render('ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ingredient_edit", methods={"GET","POST"})
     * @Security("not is_anonymous()")
     */
    public function edit(Request $request, Ingredient $ingredient): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ingredient_index');
        }

        return $this->render('ingredient/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ingredient_delete", methods={"POST"})
     * @Security("not is_anonymous()")
     */
    public function delete(Request $request, Ingredient $ingredient): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ingredient->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ingredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ingredient_index');
    }
}
