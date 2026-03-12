<?php

namespace App\DataTransferObject\ObjectMapper;

use App\DataTransferObject\RecipeDTO;
use App\Entity\Recipe;
use App\Service\DateService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RecipeObjectMapper
{
    public function __construct(
        private string $projectDir, 
        private Security $security,
        private DateService $dateService,
        private UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    public function map(Recipe $recipe): RecipeDTO
    {
        $package = new Package(new JsonManifestVersionStrategy($this->projectDir . '/public/build/manifest.json'));
        
        $mainPictureUrl = $recipe->getMainPictureFilename() ?
            $package->getUrl('/uploads/pictures/' . $recipe->getMainPictureFilename()) :
            $package->getUrl('build/image/default-recipe-main-picture.jpg');

        $recipeDto = new RecipeDTO();
        $recipeDto
            ->setId($recipe->getId())
            ->setName($recipe->getName())
            ->setMainPictureFilename($recipe->getMainPictureFilename())
            ->setMainPictureUrl($mainPictureUrl)
            ->setFullDuration($this->dateService->formatDateInterval($recipe->getFullDuration()))
            ->setEnergy($recipe->getEnergy())
            ->setCarbohydrates($recipe->getCarbohydrates())
            ->setProteins($recipe->getProteins())
            ->setFat($recipe->getFat())
            ->setAuthor($recipe->getAuthor())
            ->setCanViewAuthorUsername($this->security->isGranted('view_author_username', $recipe))
            ->setDifficultyLevel($recipe->getDifficultyLevel())
            ->setTags($recipe->getTags())
            ->setFavedBy($recipe->getFavedBy())
            ->setRecipeShowPath($this->urlGenerator->generate('recipe_show', ['id' => $recipe->getId()]))
            ->setRecipeEditPath($this->urlGenerator->generate('recipe_edit', ['id' => $recipe->getId()]))
            ->setRecipeNewPath($this->urlGenerator->generate('recipe_new', ['fromRecipe' => $recipe->getId()]))
        ;

        return $recipeDto;
    }

    public function mapAll(array $recipes): array
    {
        $recipeDtoArray = [];
        foreach ($recipes as $recipe) {
            $recipeDtoArray[] = $this->map($recipe);
        }

        return $recipeDtoArray;
    }
}
