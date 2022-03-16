<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IngredientRepository::class)
 */
class Ingredient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $barCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $shop;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $portionSize;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $measureType;

    /**
     * @ORM\Column(type="float")
     */
    private $proteins;

    /**
     * @ORM\Column(type="float")
     */
    private $carbohydrates;

    /**
     * @ORM\Column(type="float")
     */
    private $fat;

    /**
     * @ORM\Column(type="float")
     */
    private $energy;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $shopBatchSize;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $unitSize;

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

    public function getShop(): ?string
    {
        return $this->shop;
    }

    public function setShop(?string $shop): self
    {
        $this->shop = $shop;

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
}
