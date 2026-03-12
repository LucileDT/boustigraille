<?php

namespace App\DataTransferObject;

use App\Entity\DifficultyLevel;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;

class RecipeDTO implements JsonSerializable
{
    private int $id;
    private ?string $name;
    private ?string $process;
    private ?string $comment;
    private ?string $mainPictureFilename;
    private ?string $mainPictureUrl;
    private ?string $fullDuration;
    private ?float $energy;
    private ?float $carbohydrates;
    private ?float $proteins;
    private ?float $fat;
    private ?bool $canViewAuthorUsername;
    private User $author;
    private Collection $ingredients;
    private Collection $favedBy;
    private Collection $tags;
    private ?DifficultyLevel $difficultyLevel = null;
    private Collection $reviews;
    private string $recipeShowPath;
    private string $recipeEditPath;
    private string $recipeNewPath;
    private string $toggleFavoritePath;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->favedBy = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getProcess(): ?string
    {
        return $this->process;
    }

    public function setProcess(?string $process): self
    {
        $this->process = $process;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection|IngredientQuantityForRecipe[]
     */
    public function getIngredients(): ?Collection
    {
        return $this->ingredients;
    }

    public function setIngredients(?Collection $ingredients): self
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFavedBy()
    {
        return $this->favedBy;
    }

    public function setFavedBy(Collection $favedBy): self
    {
        $this->favedBy = $favedBy;

        return $this;
    }

    public function getProteins(): float
    {
        return $this->proteins;
    }

    public function setProteins(float $proteins): self
    {
        $this->proteins = $proteins;

        return $this;
    }

    public function getFat(): float
    {
        return $this->fat;
    }

    public function setFat(float $fat): self
    {
        $this->fat = $fat;

        return $this;
    }

    public function getCarbohydrates(): float
    {
        return $this->carbohydrates;
    }

    public function setCarbohydrates(float $carbohydrates): self
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    public function getEnergy(): float
    {
        return $this->energy;
    }

    public function setEnergy(float $energy): self
    {
        $this->energy = $energy;

        return $this;
    }

    public function getMainPictureFilename(): ?string
    {
        return $this->mainPictureFilename;
    }

    public function setMainPictureFilename(?string $mainPictureFilename): self
    {
        $this->mainPictureFilename = $mainPictureFilename;

        return $this;
    }

    public function getMainPictureUrl(): ?string
    {
        return $this->mainPictureUrl;
    }

    public function setMainPictureUrl(?string $mainPictureUrl): self
    {
        $this->mainPictureUrl = $mainPictureUrl;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTags(Collection $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getFullDuration(): string
    {
        return $this->fullDuration;
    }

    public function setFullDuration(?string $fullDuration): self
    {
        $this->fullDuration = $fullDuration;

        return $this;
    }

    public function getDifficultyLevel(): ?DifficultyLevel
    {
        return $this->difficultyLevel;
    }

    public function setDifficultyLevel(?DifficultyLevel $difficultyLevel): self
    {
        $this->difficultyLevel = $difficultyLevel;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function setReviews(Collection $reviews): self
    {
        $this->reviews = $reviews;

        return $this;
    }

    public function getCanViewAuthorUsername()
    {
        return $this->canViewAuthorUsername;
    }

    public function setCanViewAuthorUsername($canViewAuthorUsername): self
    {
        $this->canViewAuthorUsername = $canViewAuthorUsername;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'main_picture_filename' => $this->getMainPictureFilename(),
            'main_picture_url' => $this->getMainPictureUrl(),
            'full_duration' => $this->getFullDuration(),
            'energy' => $this->getEnergy(),
            'fat' => $this->getFat(),
            'carbohydrates' => $this->getCarbohydrates(),
            'proteins' => $this->getProteins(),
            'author' => $this->getAuthor(),
            'can_view_author_username' => $this->getCanViewAuthorUsername(),
            'difficulty_level' => $this->getDifficultyLevel(),
            'tags' => $this->getTags()->toArray(),
            'faved_by' => $this->getFavedBy()->toArray(),
            'recipe_show_path' => $this->getRecipeShowPath(),
            'recipe_edit_path' => $this->getRecipeEditPath(),
            'recipe_new_path' => $this->getRecipeNewPath(),
            'toggle_favorite_path' => $this->getToggleFavoritePath(),
        ];
    }

    /**
     * Get the value of recipeShowPath
     */
    public function getRecipeShowPath()
    {
        return $this->recipeShowPath;
    }

    /**
     * Set the value of recipeShowPath
     *
     * @return  self
     */
    public function setRecipeShowPath($recipeShowPath)
    {
        $this->recipeShowPath = $recipeShowPath;

        return $this;
    }

    /**
     * Get the value of recipeEditPath
     */
    public function getRecipeEditPath()
    {
        return $this->recipeEditPath;
    }

    /**
     * Set the value of recipeEditPath
     *
     * @return  self
     */
    public function setRecipeEditPath($recipeEditPath)
    {
        $this->recipeEditPath = $recipeEditPath;

        return $this;
    }

    /**
     * Get the value of recipeNewPath
     */
    public function getRecipeNewPath()
    {
        return $this->recipeNewPath;
    }

    /**
     * Set the value of recipeNewPath
     *
     * @return  self
     */
    public function setRecipeNewPath($recipeNewPath)
    {
        $this->recipeNewPath = $recipeNewPath;

        return $this;
    }

    /**
     * Get the value of toggleFavoritePath
     */
    public function getToggleFavoritePath()
    {
        return $this->toggleFavoritePath;
    }

    /**
     * Set the value of toggleFavoritePath
     *
     * @return  self
     */
    public function setToggleFavoritePath($toggleFavoritePath)
    {
        $this->toggleFavoritePath = $toggleFavoritePath;

        return $this;
    }
}
