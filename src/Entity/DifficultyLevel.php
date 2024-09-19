<?php

namespace App\Entity;

use App\Repository\DifficultyLevelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DifficultyLevelRepository::class)]
class DifficultyLevel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 511)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'difficultyLevel', targetEntity: Recipe::class)]
    private Collection $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setDifficultyLevel($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getDifficultyLevel() === $this) {
                $recipe->setDifficultyLevel(null);
            }
        }

        return $this;
    }

    /**
     * Format the DifficultyLevel as a string
     * E.g. "Facile (Repas réalisable par n'importe qui et qui ne requiert aucun outil particulier)"
     *
     * @return string a string containing both the DifficultyLevel label and its description
     */
    public function getSelectName(): string
    {
        return $this->label . ' (' . strtolower($this->description) . ')';
    }
}
