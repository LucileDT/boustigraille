<?php

namespace App\Entity;

use App\Repository\FollowTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FollowTypeRepository::class)]
class FollowType
{
    const MEAL_LIST = 'meal_list';
    const USERNAME_ON_RECIPE = 'username_on_recipe';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: FollowProposition::class)]
    private Collection $followPropositions;

    public function __construct()
    {
        $this->followPropositions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, FollowProposition>
     */
    public function getFollowPropositions(): Collection
    {
        return $this->followPropositions;
    }

    public function addFollowProposition(FollowProposition $followProposition): static
    {
        if (!$this->followPropositions->contains($followProposition)) {
            $this->followPropositions->add($followProposition);
            $followProposition->setType($this);
        }

        return $this;
    }

    public function removeFollowProposition(FollowProposition $followProposition): static
    {
        if ($this->followPropositions->removeElement($followProposition)) {
            // set the owning side to null (unless already changed)
            if ($followProposition->getType() === $this) {
                $followProposition->setType(null);
            }
        }

        return $this;
    }
}
