<?php

namespace App\Entity;

use App\Repository\ActionRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActionRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "follow_meal_list" = "FollowMealList",
 *     "follow_username_on_recipe" = "FollowUsernameOnRecipe",
 * })
 */
abstract class Action
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
    private ?DateTimeImmutable $proposedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $acceptedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $refusedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProposedAt(): ?DateTimeImmutable
    {
        return $this->proposedAt;
    }

    public function setProposedAt(DateTimeImmutable $proposedAt): self
    {
        $this->proposedAt = $proposedAt;

        return $this;
    }

    public function getAcceptedAt(): ?DateTimeImmutable
    {
        return $this->acceptedAt;
    }

    public function setAcceptedAt(?DateTimeImmutable $acceptedAt): self
    {
        $this->acceptedAt = $acceptedAt;

        return $this;
    }

    public function getRefusedAt(): ?DateTimeImmutable
    {
        return $this->refusedAt;
    }

    public function setRefusedAt(?DateTimeImmutable $refusedAt): self
    {
        $this->refusedAt = $refusedAt;

        return $this;
    }
}
