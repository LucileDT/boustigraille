<?php

namespace App\Entity;

use App\Repository\FollowMealListRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FollowMealListRepository::class)
 */
class FollowMealList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $proposedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $acceptedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $refusedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="followingMealLists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $follower;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="followerMealLists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $followed;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProposedAt(): ?\DateTimeImmutable
    {
        return $this->proposedAt;
    }

    public function setProposedAt(\DateTimeImmutable $proposedAt): self
    {
        $this->proposedAt = $proposedAt;

        return $this;
    }

    public function getAcceptedAt(): ?\DateTimeImmutable
    {
        return $this->acceptedAt;
    }

    public function setAcceptedAt(?\DateTimeImmutable $acceptedAt): self
    {
        $this->acceptedAt = $acceptedAt;

        return $this;
    }
    
    public function getRefusedAt(): ?\DateTimeImmutable
    {
        return $this->refusedAt;
    }

    public function setRefusedAt(?\DateTimeImmutable $refusedAt): self
    {
        $this->refusedAt = $refusedAt;

        return $this;
    }

    public function getFollower(): ?User
    {
        return $this->follower;
    }

    public function setFollower(?User $follower): self
    {
        $this->follower = $follower;

        return $this;
    }

    public function getFollowed(): ?User
    {
        return $this->followed;
    }

    public function setFollowed(?User $followed): self
    {
        $this->followed = $followed;

        return $this;
    }
}
