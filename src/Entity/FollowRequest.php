<?php

namespace App\Entity;

use App\Entity\Trait\Actionnable;
use App\Repository\FollowRequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FollowRequestRepository::class)]
class FollowRequest
{
    use Actionnable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'followRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FollowType $type = null;

    #[ORM\ManyToOne(inversedBy: 'followRequestsSent')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $follower = null;

    #[ORM\ManyToOne(inversedBy: 'followRequestsReceived')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $followed = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?FollowType
    {
        return $this->type;
    }

    public function setType(?FollowType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getFollower(): ?User
    {
        return $this->follower;
    }

    public function setFollower(?User $follower): static
    {
        $this->follower = $follower;

        return $this;
    }

    public function getFollowed(): ?User
    {
        return $this->followed;
    }

    public function setFollowed(?User $followed): static
    {
        $this->followed = $followed;

        return $this;
    }
}
