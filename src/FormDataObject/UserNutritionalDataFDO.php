<?php

namespace App\FormDataObject;

class UserNutritionalDataFDO
{
    private $proteins;
    private $fat;
    private $carbohydrates;
    private $energy;

    public function getProteins(): float
    {
        return $this->proteins;
    }

    public function getFat(): float
    {
        return $this->fat;
    }

    public function getCarbohydrates(): float
    {
        return $this->carbohydrates;
    }

    public function getEnergy(): float
    {
        return $this->energy;
    }

    public function setProteins(float $proteins): self
    {
        $this->proteins = $proteins;

        return $this;
    }

    public function setFat(float $fat): self
    {
        $this->fat = $fat;

        return $this;
    }

    public function setCarbohydrates(float $carbohydrates): self
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    public function setEnergy(float $energy): self
    {
        $this->energy = $energy;

        return $this;
    }
}
