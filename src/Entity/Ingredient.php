<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 40, nullable: true)]
    private ?string $barCode;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $label;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $brand;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $portionSize;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $measureType;

    #[ORM\Column(type: 'float')]
    private ?float $proteins;

    #[ORM\Column(type: 'float')]
    private ?float $carbohydrates;

    #[ORM\Column(type: 'float')]
    private ?float $fat;

    #[ORM\Column(type: 'float')]
    private ?float $energy;

    #[ORM\Column(type: 'string', length: 1000, nullable: true)]
    private ?string $comment;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $shopBatchSize;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $unitSize;

    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: Store::class, inversedBy: 'ingredients')]
    private ?Store $store;

    #[ORM\Column(type: 'boolean')]
    private ?bool $hasStockCheckNeededBeforeBuying;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: IngredientQuantityForRecipe::class, cascade: ['persist'], orphanRemoval: true)]
    private $ingredientQuantityForRecipes;

    /**
     * @var DateTimeImmutable|null Last OpenFoodFacts synchronization date
     */
    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $lastSynchronizedAt = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'ingredients')]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getPortionSize(): ?int
    {
        return $this->portionSize;
    }

    public function setPortionSize(?int $portionSize): self
    {
        $this->portionSize = $portionSize;

        return $this;
    }

    public function getMeasureType(): ?string
    {
        return $this->measureType;
    }

    public function setMeasureType(string $measureType): self
    {
        $this->measureType = $measureType;

        return $this;
    }

    public function getProteins(): ?float
    {
        return $this->proteins;
    }

    public function setProteins(float $proteins): self
    {
        $this->proteins = $proteins;

        return $this;
    }

    public function getCarbohydrates(): ?float
    {
        return $this->carbohydrates;
    }

    public function setCarbohydrates(?float $carbohydrates): self
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    public function getFat(): ?float
    {
        return $this->fat;
    }

    public function setFat(?float $fat): self
    {
        $this->fat = $fat;

        return $this;
    }

    public function getEnergy(): ?float
    {
        return $this->energy;
    }

    public function setEnergy(float $energy): self
    {
        $this->energy = $energy;

        return $this;
    }

    public function getBarCode(): ?string
    {
        return $this->barCode;
    }

    public function setBarCode(?string $barCode): self
    {
        $this->barCode = $barCode;

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

    public function getShopBatchSize(): ?float
    {
        return $this->shopBatchSize;
    }

    public function setShopBatchSize(?float $shopBatchSize): self
    {
        $this->shopBatchSize = $shopBatchSize;

        return $this;
    }

    public function getUnitSize(): ?float
    {
        return $this->unitSize;
    }

    public function setUnitSize(?float $unitSize): self
    {
        $this->unitSize = $unitSize;

        return $this;
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $store = null): self
    {
        $this->store = $store;

        return $this;
    }

    public function hasStockCheckNeededBeforeBuying(): ?bool
    {
        return $this->hasStockCheckNeededBeforeBuying;
    }

    public function setHasStockCheckNeededBeforeBuying(bool $hasStockCheckNeededBeforeBuying): self
    {
        $this->hasStockCheckNeededBeforeBuying = $hasStockCheckNeededBeforeBuying;

        return $this;
    }

    /**
     * @return Collection|IngredientQuantityForRecipe[]
     */
    public function getIngredientQuantityForRecipes(): ?Collection
    {
        return $this->ingredientQuantityForRecipes;
    }

    /**
     * Return the last OpenFoodFacts synchronization date
     * @return DateTimeImmutable|null
     */
    public function getLastSynchronizedAt(): ?DateTimeImmutable
    {
        return $this->lastSynchronizedAt;
    }

    /**
     * Set the last OpenFoodFacts synchronization date
     * @param DateTimeImmutable|null $lastSynchronizedAt
     * @return $this
     */
    public function setLastSynchronizedAt(?DateTimeImmutable $lastSynchronizedAt): static
    {
        $this->lastSynchronizedAt = $lastSynchronizedAt;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function isVegan(): bool
    {

    }
}
