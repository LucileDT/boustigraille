<?php

namespace App\Entity;

use App\Repository\FollowUsernameOnRecipeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FollowUsernameOnRecipeRepository::class)
 */
class FollowUsernameOnRecipe extends Action
{
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="followingUsernamesOnRecipes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $follower;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="followerUsernamesOnRecipes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $followed;

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
