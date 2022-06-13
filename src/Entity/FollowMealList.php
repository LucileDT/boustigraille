<?php

namespace App\Entity;

use App\Repository\FollowMealListRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FollowMealListRepository::class)
 */
class FollowMealList extends Action
{
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="followingMealLists")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $follower;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="followerMealLists")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $followed;

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
