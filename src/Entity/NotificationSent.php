<?php

namespace App\Entity;

use App\Repository\NotificationSentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationSentRepository::class)]
class NotificationSent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'notificationSents')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $sender = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $sentAt = null;

    #[ORM\OneToOne(inversedBy: 'notificationSent', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?NotificationHistory $notificationHistory = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getSentAt(): ?\DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeImmutable $sentAt): static
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getNotificationHistory(): ?NotificationHistory
    {
        return $this->notificationHistory;
    }

    public function setNotificationHistory(NotificationHistory $notificationHistory): static
    {
        $this->notificationHistory = $notificationHistory;

        return $this;
    }
}
