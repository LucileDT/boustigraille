<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientFromOpenFoodFactsType;
use App\Form\IngredientType;
use App\FormDataObject\IngredientFromOpenFoodFactsFDO;
use App\Repository\IngredientRepository;
use App\Service\OpenFoodFactService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/ingredient')]
class IngredientController extends AbstractController
{
    #[Route(path: '/', name: 'ingredient_index', methods: ['GET'])]
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

    #[Route(path: '/new', name: 'ingredient_new', methods: ['GET', 'POST'])]
    #[Security('not is_anonymous()')]
    public function new(Request $request): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        $ingredientFromOpenFoodFactsFDO = new IngredientFromOpenFoodFactsFDO();
        $formFromOpenFoodFact = $this->createForm(IngredientFromOpenFoodFactsType::class, $ingredientFromOpenFoodFactsFDO, [
            'action' => $this->generateUrl('ingredient_new_from_openfoodfacts'),
        ]);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ingredient);
            $entityManager->flush();

            return $this->redirectToRoute('ingredient_index');
        }

        return $this->render('ingredient/new.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form->createView(),
            'formFromOpenFoodFact' => $formFromOpenFoodFact->createView(),
            'addOpenFoodFactsForm' => true,
        ]);
    }

    #[Route(path: '/new-from-openfoodfacts', name: 'ingredient_new_from_openfoodfacts', methods: ['POST'])]
    #[Security('not is_anonymous()')]
    public function newFromOpenFoodFacts(
        EntityManagerInterface $entityManager,
        OpenFoodFactService $offService,
        Request $request,
        IngredientFromOpenFoodFactsFDO $ingredientIdentifier): Response
    {
        $form = $this->createForm(IngredientFromOpenFoodFactsType::class, $ingredientIdentifier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productBarCode = '';
            if (filter_var($ingredientIdentifier->getIdentifier(), FILTER_VALIDATE_URL) !== false)
            {
                $productUrl = $ingredientIdentifier->getIdentifier();

                try
                {
                    $productBarCode = $offService->getBarCodeFromProductUrl($productUrl);
                }
                catch (Exception $ex)
                {
                    $this->addFlash('danger', $ex->getMessage());

                    // try to get the previous page or fallback on home page
                    $this->tryToRedirectToLastUrl($request);
                }
            }
            else
            {
                $productBarCode = $ingredientIdentifier->getIdentifier();
            }

            try
            {
                $ingredient = new Ingredient();
                $product = $offService->getProductFromApi($productBarCode);

                $offService->fillIngredientNutritionalDataWithProductOnes($ingredient, $product);
                $form = $this->createForm(IngredientType::class, $ingredient, [
                        'action' => $this->generateUrl('ingredient_new'),
                    ]);

                return $this->render('ingredient/new.html.twig', [
                    'ingredient' => $ingredient,
                    'form' => $form->createView(),
                    'addOpenFoodFactsForm' => false,
                ]);
            }
            catch (Exception $ex)
            {
                $this->addFlash('danger', $ex->getMessage());

                // try to get the previous page or fallback on home page
                return $this->tryToRedirectToLastUrl($request);
            }
        }
        else
        {
            return $this->tryToRedirectToLastUrl($request);
        }
    }

    #[Route(path: '/{id}', name: 'ingredient_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Ingredient $ingredient): Response
    {
        return $this->render('ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

    #[Route(path: '/{id}/edit', name: 'ingredient_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    #[Security('not is_anonymous()')]
    public function edit(EntityManagerInterface $entityManager, Request $request, Ingredient $ingredient): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('ingredient_index');
        }

        return $this->render('ingredient/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form->createView(),
            'addOpenFoodFactsForm' => false,
        ]);
    }

    #[Route(path: '/{id}', name: 'ingredient_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    #[Security('not is_anonymous()')]
    public function delete(Request $request, Ingredient $ingredient): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ingredient->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ingredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ingredient_index');
    }

    /**
     * Tries to redirect to the last url or fallback on the homepage.
     */
    private function tryToRedirectToLastUrl(Request $request)
    {
        $referer = $request->headers->get('referer');
        if (empty($referer))
        {
            $url = $this->generateUrl('home');
        }
        else
        {
            $url = $referer;
        }

        return $this->redirect($url);
    }
}
