<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 */
class Recipe implements JsonSerializable
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

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="favoriteRecipes")
     * @ORM\OrderBy({"username" = "ASC"})
     */
    private $favedBy;

    /**
     * @ORM\OneToMany(targetEntity=MealQuantityForList::class, mappedBy="meal", cascade={"persist"})
     */
    private $mealQuantityForLists;

    public function getMealQuantityForLists()
    {
        return $this->mealQuantityForLists;
    }

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->favedBy = new ArrayCollection();
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
     * @return ArrayCollection|User[]
     */
    public function getFavedBy()
    {
        return $this->favedBy;
    }

    public function isFavedBy(User $user): bool
    {
        return $this->favedBy->contains($user);
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
                $proteinsCount += ($ingredient->getIngredient()->getProteins() * $ingredient->getIngredient()->getUnitSize() / 100) * $ingredient->getQuantity();
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
                $fatCount += ($ingredient->getIngredient()->getFat() * $ingredient->getIngredient()->getUnitSize() / 100) * $ingredient->getQuantity();
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
                $carbohydratesCount += ($ingredient->getIngredient()->getCarbohydrates() * $ingredient->getIngredient()->getUnitSize() / 100) * $ingredient->getQuantity();
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
                $energyCount += ($ingredient->getIngredient()->getEnergy() * $ingredient->getIngredient()->getUnitSize() / 100) * $ingredient->getQuantity();
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'main_picture_filename' => $this->getMainPictureFilename(),
            'energy' => $this->getEnergy(),
            'author' => $this->getAuthor(),
        ];
    }
}
