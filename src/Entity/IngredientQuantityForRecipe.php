<?php

namespace App\Entity;

use App\Repository\IngredientQuantityForRecipeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IngredientQuantityForRecipeRepository::class)
 */
class IngredientQuantityForRecipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Ingredient::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $ingredient;

    /**
     * @ORM\Column(type="float")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Recipe::class, inversedBy="ingredients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipe;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isMeasuredByUnit;

    public function __construct(?IngredientQuantityForRecipe $ingredient = null)
    {
        if (!empty($ingredient)) {
            $this->setIngredient($ingredient->getIngredient());
            $this->setQuantity($ingredient->getQuantity());
            $this->setIsMeasuredByUnit($ingredient->isMeasuredByUnit());
            $this->setRecipe($ingredient->getRecipe());
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(Ingredient $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function isMeasuredByUnit(): ?bool
    {
        return $this->isMeasuredByUnit;
    }

    public function setIsMeasuredByUnit(bool $isMeasuredByUnit): self
    {
        $this->isMeasuredByUnit = $isMeasuredByUnit;

        return $this;
    }
}
