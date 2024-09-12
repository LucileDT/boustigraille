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

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: FollowRequest::class)]
    private Collection $followRequests;

    public function __construct()
    {
        $this->followRequests = new ArrayCollection();
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
     * @return Collection<int, FollowRequest>
     */
    public function getFollowRequests(): Collection
    {
        return $this->followRequests;
    }

    public function addFollowRequest(FollowRequest $followRequest): static
    {
        if (!$this->followRequests->contains($followRequest)) {
            $this->followRequests->add($followRequest);
            $followRequest->setType($this);
        }

        return $this;
    }

    public function removeFollowRequest(FollowRequest $followRequest): static
    {
        if ($this->followRequests->removeElement($followRequest)) {
            // set the owning side to null (unless already changed)
            if ($followRequest->getType() === $this) {
                $followRequest->setType(null);
            }
        }

        return $this;
    }
}
