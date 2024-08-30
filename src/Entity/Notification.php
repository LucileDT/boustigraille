<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $message;

    #[ORM\Column(type: 'date')]
    private $dateSent;

    #[ORM\OneToMany(targetEntity: NotificationReceipt::class, mappedBy: 'notification', orphanRemoval: true)]
    private $notificationReceipts;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'sentNotifications')]
    private $sender;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: NotificationCategory::class, inversedBy: 'notifications')]
    private $category;

    #[ORM\OneToOne(targetEntity: Action::class, cascade: ['persist', 'remove'])]
    private $action;

    public function __construct()
    {
        $this->notificationReceipts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDateSent(): ?\DateTimeInterface
    {
        return $this->dateSent;
    }

    public function setDateSent(\DateTimeInterface $dateSent): self
    {
        $this->dateSent = $dateSent;

        return $this;
    }

    /**
     * @return Collection<int, NotificationReceipt>
     */
    public function getNotificationReceipts(): Collection
    {
        return $this->notificationReceipts;
    }

    public function addNotificationReceipt(NotificationReceipt $notificationReceipt): self
    {
        if (!$this->notificationReceipts->contains($notificationReceipt)) {
            $this->notificationReceipts[] = $notificationReceipt;
            $notificationReceipt->setNotification($this);
        }

        return $this;
    }

    public function removeNotificationReceipt(NotificationReceipt $notificationReceipt): self
    {
        if ($this->notificationReceipts->removeElement($notificationReceipt)) {
            // set the owning side to null (unless already changed)
            if ($notificationReceipt->getNotification() === $this) {
                $notificationReceipt->setNotification(null);
            }
        }

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getCategory(): ?NotificationCategory
    {
        return $this->category;
    }

    public function setCategory(?NotificationCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAction(): ?Action
    {
        return $this->action;
    }

    public function setAction(?Action $action): self
    {
        $this->action = $action;

        return $this;
    }
}
