<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\MappedSuperclass]
abstract class Meeting {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\Column]
    private ?bool $isCompleted = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $scheduledAt = null;

    #[Pure] public function __construct() {
    }

    use TimestampableTrait;

    public function getId(): ?int {
        return $this->id;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;

        return $this;
    }

    public function getLink(): ?string {
        return $this->link;
    }

    public function setLink(?string $link): self {
        $this->link = $link;

        return $this;
    }

    public function isIsCompleted(): ?bool {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted): self {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    public function getScheduledAt(): ?\DateTimeInterface {
        return $this->scheduledAt;
    }

    public function setScheduledAt(\DateTimeInterface $scheduledAt): self {
        $this->scheduledAt = $scheduledAt;

        return $this;
    }
}
