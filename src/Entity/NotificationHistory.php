<?php

namespace App\Entity;

use App\Repository\NotificationHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationHistoryRepository::class)]
class NotificationHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column(length: 1, nullable: true)]
    private ?string $emoji = null;

    #[ORM\Column]
    private ?int $importance = null;

    #[ORM\Column(type: Types::JSON)]
    private array $channels = [];

    #[ORM\OneToOne(mappedBy: 'notificationHistory', cascade: ['persist', 'remove'])]
    private ?NotificationSent $notificationSent = null;

    #[ORM\OneToOne(mappedBy: 'notificationHistory', cascade: ['persist', 'remove'])]
    private ?NotificationReceived $notificationReceived = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getEmoji(): ?string
    {
        return $this->emoji;
    }

    public function setEmoji(?string $emoji): static
    {
        $this->emoji = $emoji;

        return $this;
    }

    public function getImportance(): ?int
    {
        return $this->importance;
    }

    public function setImportance(int $importance): static
    {
        $this->importance = $importance;

        return $this;
    }

    public function getChannels(): array
    {
        return $this->channels;
    }

    public function setChannels(array $channels): static
    {
        $this->channels = $channels;

        return $this;
    }

    public function getNotificationSent(): ?NotificationSent
    {
        return $this->notificationSent;
    }

    public function setNotificationSent(NotificationSent $notificationSent): static
    {
        // set the owning side of the relation if necessary
        if ($notificationSent->getNotificationHistory() !== $this) {
            $notificationSent->setNotificationHistory($this);
        }

        $this->notificationSent = $notificationSent;

        return $this;
    }

    public function getNotificationReceived(): ?NotificationReceived
    {
        return $this->notificationReceived;
    }

    public function setNotificationReceived(NotificationReceived $notificationReceived): static
    {
        // set the owning side of the relation if necessary
        if ($notificationReceived->getNotificationHistory() !== $this) {
            $notificationReceived->setNotificationHistory($this);
        }

        $this->notificationReceived = $notificationReceived;

        return $this;
    }
}
