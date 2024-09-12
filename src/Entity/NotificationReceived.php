<?php

namespace App\Entity;

use App\Repository\NotificationReceivedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationReceivedRepository::class)]
class NotificationReceived
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'notificationsReceived')]
    private ?User $recipient = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $readAt = null;

    #[ORM\OneToOne(inversedBy: 'notificationReceived', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?NotificationHistory $notificationHistory = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): static
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getReadAt(): ?\DateTimeImmutable
    {
        return $this->readAt;
    }

    public function setReadAt(?\DateTimeImmutable $readAt): static
    {
        $this->readAt = $readAt;

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
