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
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255)
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
     * @ORM\Column(type="integer")
     */
    private $proteins;

    /**
     * @ORM\Column(type="integer")
     */
    private $carbohydrates;

    /**
     * @ORM\Column(type="integer")
     */
    private $fat;

    /**
     * @ORM\Column(type="integer")
     */
    private $energy;

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

    public function setBrand(string $brand): self
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

    public function getProteins(): ?int
    {
        return $this->proteins;
    }

    public function setProteins(int $proteins): self
    {
        $this->proteins = $proteins;

        return $this;
    }

    public function getCarbohydrates(): ?int
    {
        return $this->carbohydrates;
    }

    public function setCarbohydrates(?int $carbohydrates): self
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    public function getFat(): ?int
    {
        return $this->fat;
    }

    public function setFat(?int $fat): self
    {
        $this->fat = $fat;

        return $this;
    }

    public function getEnergy(): ?int
    {
        return $this->energy;
    }

    public function setEnergy(int $energy): self
    {
        $this->energy = $energy;

        return $this;
    }
}
