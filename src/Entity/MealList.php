<?php

namespace App\Entity;

use App\Repository\MealListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MealListRepository::class)
 */
class MealList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $personName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity=MealQuantityForList::class, mappedBy="mealList", cascade={"persist"}, orphanRemoval=true)
     */
    private $meals;

    /**
     * @ORM\Column(type="date")
     */
    private $startDate;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $isStartingAtLunch = false;

    /**
     * @ORM\Column(type="date")
     */
    private $endDate;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isEndingAtLunch = true;

    public function __construct()
    {
        $this->meals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPersonName(): ?string
    {
        return $this->personName;
    }

    public function setPersonName(string $personName): self
    {
        $this->personName = $personName;

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
     * @return Collection<int, Meal>
     */
    public function getMeals(): Collection
    {
        return $this->meals;
    }

    public function addMeal(MealQuantityForList $meal): self
    {
        if (!$this->meals->contains($meal)) {
            $this->meals[] = $meal;
            $meal->setMealList($this);
        }

        return $this;
    }

    public function removeMeal(MealQuantityForList $meal): self
    {
        if ($this->meals->removeElement($meal)) {
            // set the owning side to null (unless already changed)
            if ($meal->getMealList() === $this) {
                $meal->setMealList(null);
            }
        }

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getIsStartingAtLunch(): ?bool
    {
        return $this->isStartingAtLunch;
    }

    public function setIsStartingAtLunch(bool $isStartingAtLunch): self
    {
        $this->isStartingAtLunch = $isStartingAtLunch;

        return $this;
    }

    public function getIsEndingAtLunch(): ?bool
    {
        return $this->isEndingAtLunch;
    }

    public function setIsEndingAtLunch(bool $isEndingAtLunch): self
    {
        $this->isEndingAtLunch = $isEndingAtLunch;

        return $this;
    }
}
