<?php

namespace App\Controller;

use App\Entity\IngredientQuantityForRecipe;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Form\MarkRecipeAsFavoriteType;
use App\FormDataObject\RecipeFDO;
use App\FormDataObject\FavoriteRecipeFDO;
use App\Repository\RecipeRepository;
use App\Service\RecipeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recipe")
 */
class RecipeController extends AbstractController
{
    /**
     * @Route("/", name="recipe_index", methods={"GET"})
     */
    public function index(RecipeRepository $recipeRepository): Response
    {
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipeRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    /**
     * @Route("/new", name="recipe_new", methods={"GET","POST"})
     * @Security("not is_anonymous()")
     */
    public function new(Request $request): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $mainPicture = $form->get('main_picture')->getData();
            if ($mainPicture)
            {
                $filename = RecipeService::saveMainPicture(
                        $mainPicture,
                        $recipe->getName(),
                        $this->getParameter('pictures_directory')
                );
                $recipe->setMainPictureFilename($filename);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('recipe_show',['id' => $recipe->getId()]);
        }

        return $this->render('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recipe_show", methods={"GET", "POST"})
     */
    public function show(Request $request, Recipe $recipe): Response
    {
        $connectedUser = $this->getUser();
        if (empty($connectedUser))
        {
            return $this->render('recipe/show.html.twig', [
                'recipe' => $recipe,
            ]);
        }

        $favoriteRecipeFDO = new FavoriteRecipeFDO($connectedUser->hasFaved($recipe));
        $form = $this->createForm(MarkRecipeAsFavoriteType::class, $favoriteRecipeFDO, [
            'attr' => ['id' => 'toggle-favorite']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($favoriteRecipeFDO->isMarkedAsFavorite())
            {
                $connectedUser->addFavoriteRecipe($recipe);
            }
            else
            {
                $connectedUser->removeFavoriteRecipe($recipe);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($connectedUser);
            $entityManager->persist($recipe);
            $entityManager->flush();
        }

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
            'toggleFavoriteForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="recipe_edit", methods={"GET","POST"})
     * @Security("not is_anonymous()")
     */
    public function edit(Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $mainPicture = $form->get('main_picture')->getData();
            if ($mainPicture)
            {
                $filename = RecipeService::saveMainPicture(
                        $mainPicture,
                        $recipe->getName(),
                        $this->getParameter('pictures_directory')
                );
                $recipe->setMainPictureFilename($filename);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recipe_show',['id' => $recipe->getId()]);
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recipe_delete", methods={"POST"})
     * @Security("not is_anonymous()")
     */
    public function delete(Request $request, Recipe $recipe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('recipe_index');
    }
}
