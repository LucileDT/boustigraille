<?php

namespace App\Entity;

use App\Repository\MealQuantityForListRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MealQuantityForListRepository::class)
 */
class MealQuantityForList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=MealList::class, inversedBy="meals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mealList;

    /**
     * @ORM\ManyToOne(targetEntity=Recipe::class, inversedBy="mealQuantityForLists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $meal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getMealList(): ?MealList
    {
        return $this->mealList;
    }

    public function setMealList(?MealList $mealList): self
    {
        $this->mealList = $mealList;

        return $this;
    }

    public function getMeal(): ?Recipe
    {
        return $this->meal;
    }

    public function setMeal(?Recipe $meal): self
    {
        $this->meal = $meal;

        return $this;
    }
}
