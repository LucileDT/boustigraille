<?php

namespace App\FormDataObject;

class FavoriteRecipeFDO
{
    private $isMarkedAsFavorite;

    public function __construct(bool $isFaved)
    {
        $this->setIsMarkedAsFavorite($isFaved);
    }

    public function isMarkedAsFavorite(): ?bool
    {
        return $this->isMarkedAsFavorite;
    }

    public function setIsMarkedAsFavorite(bool $isMarkedAsFavorite): self
    {
        $this->isMarkedAsFavorite = $isMarkedAsFavorite;

        return $this;
    }
}
