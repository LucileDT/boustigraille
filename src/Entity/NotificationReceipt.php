<?php

namespace App\Entity;

use App\Repository\NotificationReceiptRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NotificationReceiptRepository::class)
 */
class NotificationReceipt
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateRead;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="notificationReceipts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipient;

    /**
     * @ORM\ManyToOne(targetEntity=Notification::class, inversedBy="notificationReceipts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $notification;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRead(): ?\DateTimeInterface
    {
        return $this->dateRead;
    }

    public function setDateRead(?\DateTimeInterface $dateRead): self
    {
        $this->dateRead = $dateRead;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    public function setNotification(?Notification $notification): self
    {
        $this->notification = $notification;

        return $this;
    }

    public function getColor(): string
    {
        if (empty($this->dateRead)) {
            return $this->getNotification()->getCategory()->getColor();
        } else {
            return 'dark';
        }
    }
}
