<?php

namespace App\FormDataObject;

class IngredientFromOpenFoodFactsFDO
{
    private $identifier;

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }
}
