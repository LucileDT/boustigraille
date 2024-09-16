<?php

namespace App\Controller;

use App\Entity\IngredientQuantityForRecipe;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Repository\ReviewRepository;
use App\Repository\TagRepository;
use App\Service\Migrations\ContentAuthorService;
use App\Service\RecipeService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/recipe', name: 'recipe_')]
class RecipeController extends AbstractController
{
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository): Response
    {
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipeRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    #[Route(path: '/new/{fromRecipe?}', name: 'new', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function new(Request $request, EntityManagerInterface $entityManager, ?Recipe $fromRecipe): Response
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
     * @throws Exception
     */
    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(
        Recipe $recipe,
        ContentAuthorService $authorService,
        ReviewRepository $reviewRepository
    ): Response
    {
        $authorService->updateRecipesAuthor();
        $connectedUser = $this->getUser();
        $review = null;

        if (!empty($connectedUser)) {
            $review = $reviewRepository->findOneBy([
                'author' => $connectedUser,
                'recipe' => $recipe,
            ]);
        }

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
            'userReview' => $review,
        ]);
    }

    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function edit(
        Request $request,
        Recipe $recipe,
        EntityManagerInterface $entityManager,
        TagRepository $tagRepository
    ): Response
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

            // make sure tags correspond to the difficulty level
            if ($recipe->getDifficultyLevel()?->getLabel() === 'Facile') {
                $recipe->addTag($tagRepository->findOneBy(['label' => 'Facile']));
                $recipe->removeTag($tagRepository->findOneBy(['label' => 'Difficile']));
            } else if ($recipe->getDifficultyLevel()?->getLabel() === 'Difficile') {
                $recipe->addTag($tagRepository->findOneBy(['label' => 'Difficile']));
                $recipe->removeTag($tagRepository->findOneBy(['label' => 'Facile']));
            } else {
                $recipe->removeTag($tagRepository->findOneBy(['label' => 'Facile']));
                $recipe->removeTag($tagRepository->findOneBy(['label' => 'Difficile']));
            }

            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('recipe_show',['id' => $recipe->getId()]);
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}', name: 'delete', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function delete(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            foreach ($recipe->getIngredients() as $ingredientQuantity) {
                $entityManager->remove($ingredientQuantity);
            }
            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('recipe_index');
    }
}
