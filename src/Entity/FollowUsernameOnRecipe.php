<?php

namespace App\Entity;

use App\Repository\FollowUsernameOnRecipeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FollowUsernameOnRecipeRepository::class)]
class FollowUsernameOnRecipe extends Action
{
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'followingUsernamesOnRecipes')]
    private $follower;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'followerUsernamesOnRecipes')]
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
