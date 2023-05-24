<?php
declare(strict_types=1);

namespace App\Dto\Meeting\Contracts;

use App\Dto\Traits\IdentityTrait;
use App\Dto\Traits\IsCanceledTrait;
use App\Dto\Traits\SchedulingTrait;

class AbstractMeetingOutputDto
{
    use IdentityTrait;

    private \DateTimeImmutable $createdAt;

    private \DateTime $updatedAt;

    private bool $isCompleted;

    private string $description;

    private ?string $link;

    private \DateTime $scheduledAt;

    private float $duration;

    private string $studentFullName;

    private string $supervisorFullName;

    use SchedulingTrait;

    use IsCanceledTrait;

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string|null $link
     */
    public function setLink(?string $link): void
    {
        $this->link = $link;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return bool
     */
    public function getIsCompleted(): bool
    {
        return $this->isCompleted;
    }

    /**
     * @param bool $isCompleted
     */
    public function setIsCompleted(bool $isCompleted): void
    {
        $this->isCompleted = $isCompleted;
    }

    /**
     * @return float
     */
    public function getDuration(): float
    {
        return $this->duration;
    }

    /**
     * @param float $duration
     */
    public function setDuration(float $duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return \DateTime
     */
    public function getScheduledAt(): \DateTime
    {
        return $this->scheduledAt;
    }

    /**
     * @param \DateTime $scheduledAt
     */
    public function setScheduledAt(\DateTime $scheduledAt): void
    {
        $this->scheduledAt = $scheduledAt;
    }

    /**
     * @return string
     */
    public function getStudentFullName(): string
    {
        return $this->studentFullName;
    }

    /**
     * @param string $studentFullName
     */
    public function setStudentFullName(string $studentFullName): void
    {
        $this->studentFullName = $studentFullName;
    }

    /**
     * @return string
     */
    public function getSupervisorFullName(): string
    {
        return $this->supervisorFullName;
    }

    /**
     * @param string $supervisorFullName
     */
    public function setSupervisorFullName(string $supervisorFullName): void
    {
        $this->supervisorFullName = $supervisorFullName;
    }
}
