<?php

namespace App\Entity\Trait;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait Actionnable
{
    #[ORM\Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $proposedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $acceptedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $refusedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $processedAt;

    public function __construct()
    {
        $this->proposedAt = null;
        $this->acceptedAt = null;
        $this->refusedAt = null;
        $this->processedAt = null;
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
        if (empty($this->getProcessedAt())) {
            $this->setProcessedAt($acceptedAt);
        }

        return $this;
    }

    public function getRefusedAt(): ?DateTimeImmutable
    {
        return $this->refusedAt;
    }

    public function setRefusedAt(?DateTimeImmutable $refusedAt): self
    {
        $this->refusedAt = $refusedAt;
        if (empty($this->getProcessedAt())) {
            $this->setProcessedAt($refusedAt);
        }

        return $this;
    }

    public function getProcessedAt(): ?DateTimeImmutable
    {
        return $this->processedAt;
    }

    public function setProcessedAt(?DateTimeImmutable $processedAt): self
    {
        $this->processedAt = $processedAt;

        return $this;
    }
}
