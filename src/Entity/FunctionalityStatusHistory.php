<?php

namespace App\Entity;

use App\Repository\FunctionalityStatusHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FunctionalityStatusHistoryRepository::class)]
class FunctionalityStatusHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'history')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Functionality $functionality = null;

    #[ORM\ManyToOne(inversedBy: 'oldHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FunctionalityStatus $oldStatus = null;

    #[ORM\ManyToOne(inversedBy: 'newHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FunctionalityStatus $newStatus = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFunctionality(): ?Functionality
    {
        return $this->functionality;
    }

    public function setFunctionality(?Functionality $functionality): self
    {
        $this->functionality = $functionality;

        return $this;
    }

    public function getOldStatus(): ?FunctionalityStatus
    {
        return $this->oldStatus;
    }

    public function setOldStatus(?FunctionalityStatus $oldStatus): self
    {
        $this->oldStatus = $oldStatus;

        return $this;
    }

    public function getNewStatus(): ?FunctionalityStatus
    {
        return $this->newStatus;
    }

    public function setNewStatus(?FunctionalityStatus $newStatus): self
    {
        $this->newStatus = $newStatus;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
