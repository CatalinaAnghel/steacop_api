<?php

namespace App\Entity\Contracts;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\MappedSuperclass]
abstract class AbstractMeeting {
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

    #[ORM\Column]
    private ?bool $isCanceled = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $canceledAt = null;

    #[ORM\Column]
    private float $duration;

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

    public function isCompleted(): ?bool {
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

    public function isCanceled(): ?bool {
        return $this->isCanceled;
    }

    public function setIsCanceled(bool $isCanceled): void {
        $this->isCanceled = $isCanceled;
    }

    public function getCanceledAt(): ?\DateTimeImmutable {
        return $this->canceledAt;
    }

    public function setCanceledAt(?\DateTimeImmutable $canceledAt): void {
        $this->canceledAt = $canceledAt;
    }

    /**
     * @return float
     */
    public function getDuration(): float {
        return $this->duration;
    }

    /**
     * @param float $duration
     */
    public function setDuration(float $duration): void {
        $this->duration = $duration;
    }
}
