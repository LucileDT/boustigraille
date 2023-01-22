<?php

namespace App\Controller;

use App\Entity\IngredientQuantityForRecipe;
use App\Entity\Recipe;
use App\Entity\User;
use App\Form\RecipeType;
use App\FormDataObject\RecipeFDO;
use App\Repository\RecipeRepository;
use App\Service\Migrations\ContentAuthorService;
use App\Service\RecipeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
     * @Route("/new/{fromRecipe}", name="recipe_new", methods={"GET","POST"})
     * @Security("not is_anonymous()")
     */
    public function new(Request $request, Recipe $fromRecipe = null): Response
    {
        $recipe = new Recipe();
        if (!empty($fromRecipe)) {
            $recipe
                ->setName($fromRecipe->getName())
                ->setMainPictureFilename($fromRecipe->getMainPictureFilename())
                ->setProcess($fromRecipe->getProcess())
                ->setComment($fromRecipe->getComment())
            ;
            foreach ($fromRecipe->getIngredients() as $ingredient) {
                $newIngredient = new IngredientQuantityForRecipe($ingredient);
                $recipe->addIngredient($newIngredient);
                $newIngredient->setRecipe($recipe);
            }
        }
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $mainPicture = $form->get('main_picture')->getData();
            if ($mainPicture)
            {
                try
                {
                    $filename = RecipeService::saveMainPicture(
                        $mainPicture,
                        $recipe->getName(),
                        $this->getParameter('pictures_directory')
                    );
                    $recipe->setMainPictureFilename($filename);
                }
                catch (FileException $e)
                {
                    $this->addFlash('danger', $e->getMessage());
                }
            }
            $recipe->setAuthor($this->getUser());

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
     * @Route("/{id}", name="recipe_show", methods={"GET"})
     */
    public function show(Request $request, Recipe $recipe, ContentAuthorService $authorService): Response
    {
        $authorService->updateRecipesAuthor();
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
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
                try
                {
                    $filename = RecipeService::saveMainPicture(
                        $mainPicture,
                        $recipe->getName(),
                        $this->getParameter('pictures_directory')
                    );
                    $recipe->setMainPictureFilename($filename);
                }
                catch (FileException $e)
                {
                    $this->addFlash('danger', $e->getMessage());
                }
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
