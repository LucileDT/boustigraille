<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 */
class Recipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=IngredientQuantityForRecipe::class, mappedBy="recipe", cascade={"persist"}, orphanRemoval=true)
     */
    private $ingredients;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $process;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mainPictureFilename;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function addIngredient(IngredientQuantityForRecipe $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setRecipe($this);
        }

        return $this;
    }

    public function removeIngredient(IngredientQuantityForRecipe $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getRecipe() === $this) {
                $ingredient->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * Compute proteins count using recipe ingredients
     *
     * @return float
     */
    public function getProteins(): float
    {
        $proteinsCount = (float) 0;
        foreach ($this->getIngredients() as $ingredient)
        {
            if ($ingredient->isMeasuredByUnit()) {
                $proteinsCount += ($ingredient->getIngredient()->getProteins() * $ingredient->getIngredient()->getUnitySize() / 100) * $ingredient->getQuantity();
            } else {
                $proteinsCount += ($ingredient->getIngredient()->getProteins() / 100) * $ingredient->getQuantity();
            }
        }
        return $proteinsCount;
    }

    /**
     * Compute fat count using recipe ingredients
     *
     * @return float
     */
    public function getFat(): float
    {
        $fatCount = (float) 0;
        foreach ($this->getIngredients() as $ingredient)
        {
            if ($ingredient->isMeasuredByUnit()) {
                $fatCount += ($ingredient->getIngredient()->getFat() * $ingredient->getIngredient()->getUnitySize() / 100) * $ingredient->getQuantity();
            } else {
                $fatCount += ($ingredient->getIngredient()->getFat() / 100) * $ingredient->getQuantity();
            }
        }
        return $fatCount;
    }

    /**
     * Compute carbohydrates count using recipe ingredients
     *
     * @return float
     */
    public function getCarbohydrates(): float
    {
        $carbohydratesCount = (float) 0;
        foreach ($this->getIngredients() as $ingredient)
        {
            if ($ingredient->isMeasuredByUnit()) {
                $carbohydratesCount += ($ingredient->getIngredient()->getCarbohydrates() * $ingredient->getIngredient()->getUnitySize() / 100) * $ingredient->getQuantity();
            } else {
                $carbohydratesCount += ($ingredient->getIngredient()->getCarbohydrates() / 100) * $ingredient->getQuantity();
            }
        }
        return $carbohydratesCount;
    }

    /**
     * Compute energy count using recipe ingredients
     *
     * @return float
     */
    public function getEnergy(): float
    {
        $energyCount = (float) 0;
        foreach ($this->getIngredients() as $ingredient)
        {
            if ($ingredient->isMeasuredByUnit()) {
                $energyCount += ($ingredient->getIngredient()->getEnergy() * $ingredient->getIngredient()->getUnitySize() / 100) * $ingredient->getQuantity();
            } else {
                $energyCount += ($ingredient->getIngredient()->getEnergy() / 100) * $ingredient->getQuantity();
            }
        }
        return $energyCount;
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
}
