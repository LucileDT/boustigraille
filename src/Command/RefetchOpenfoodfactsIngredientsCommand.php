<?php

namespace App\Command;

use App\Repository\IngredientRepository;
use App\Service\OpenFoodFactService;
use App\Service\TagService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'refetch-openfoodfacts-ingredients',
    description: 'Refetch OpenFoodFacts data to fill corresponding ingredients. As OFF API specify https://openfoodfacts.github.io/openfoodfacts-server/api, there is a rate limit of 100 req/min for GET requests on product queries. This command checks this to avoid OFF denying access to Boustigraille.',
)]
class RefetchOpenfoodfactsIngredientsCommand extends Command
{
    public function __construct(
        private IngredientRepository $ingredientRepository,
        private OpenFoodFactService $openFoodFactService,
        private EntityManagerInterface $entityManager,
        private TagService $tagService,
    )
    {
        parent::__construct();
    }

    protected function configure(): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $countLastIngredientsFetched = count($this->ingredientRepository->findByFetchedFromOFFDuringLastMinute());
        $io->note(sprintf('Last ingredients fetched count: %s', $countLastIngredientsFetched));

        if ($countLastIngredientsFetched >= 100) {
            $io->error('Too much requests per minute will result in an OpenFoodFacts ban from their service, please retry in a few minutes.');
            return Command::INVALID;
        }

        $ingredientsToFetch1 = $this->ingredientRepository->findByLastSynchronizedAtNull();
        $io->note(sprintf('Fetching %s ingredients...', count($ingredientsToFetch1)));
        $this->fetchIngredientsData($io, $ingredientsToFetch1);

        $ingredientsToFetch2 = $this->ingredientRepository->findByLastSynchronizedNotNull(100 - count($ingredientsToFetch1));
        $io->note(sprintf('Re-fetching %s ingredients...', count($ingredientsToFetch2)));
        $this->fetchIngredientsData($io, $ingredientsToFetch2);

        return Command::SUCCESS;
    }

    private function fetchIngredientsData($io, $ingredientsToFetch): void
    {
        $errorsFetchCount = 0;
        foreach ($ingredientsToFetch as $ingredient) {
            try {
                $product = $this->openFoodFactService->getProductFromApi($ingredient->getBarCode());
                $this->openFoodFactService->synchronizeIngredientWithProductData($ingredient, $product);
                $this->entityManager->persist($ingredient);
                foreach ($ingredient->getIngredientQuantityForRecipes() as $ingredientQuantity) {
                    $recipe = $ingredientQuantity->getRecipe();
                    $this->tagService->manageRecipeVegeAndVeganTags($recipe);
                    $this->entityManager->persist($recipe);
                }
                $this->entityManager->flush();
            } catch (Exception $e) {
                $io->error($ingredient->getLabel() . ' ingredient fetching error: ' . $e->getMessage());
                $errorsFetchCount++;
                continue;
            }
        }
        $countIngredients = count($ingredientsToFetch) - $errorsFetchCount;
        if ($countIngredients > 0) {
            $io->success(sprintf('%s ingredients has been fetched and updated.', $countIngredients));
        }
        if ($errorsFetchCount > 0) {
            $io->error(sprintf('%s ingredients fetching resulted in an error.', $errorsFetchCount));
        }
    }
}
