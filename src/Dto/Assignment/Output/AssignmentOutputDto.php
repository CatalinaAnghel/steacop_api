<?php
declare(strict_types=1);

namespace App\Dto\Assignment\Output;

use App\Dto\Document\Output\DocumentOutputDto;
use App\Dto\Traits\IdentityTrait;

class AssignmentOutputDto {
    /**
     * @var \DateTime $dueDate
     */
    private \DateTime $dueDate;

    /**
     * @var DocumentOutputDto[]
     */
    private array $documents;

    /**
     * @var \DateTime|null $turnedInDate
     */
    private \DateTime|null $turnedInDate;

    /**
     * @var string $title
     */
    private string $title;

    /**
     * @var string $description
     */
    private string $description;

    /**
     * @var float|null $grade
     */
    private float|null $grade;

    /**
     * @var \DateTimeImmutable $createdAt
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @var \DateTime|null $updatedAt
     */
    private \DateTime|null $updatedAt;

    use IdentityTrait;

    /**
     * @return \DateTime
     */
    public function getDueDate(): \DateTime {
        return $this->dueDate;
    }

    /**
     * @param \DateTime $dueDate
     */
    public function setDueDate(\DateTime $dueDate): void {
        $this->dueDate = $dueDate;
    }

    /**
     * @return DocumentOutputDto[]
     */
    public function getDocuments(): array {
        return $this->documents;
    }

    /**
     * @param DocumentOutputDto[] $documents
     */
    public function setDocuments(array $documents): void {
        $this->documents = $documents;
    }

    /**
     * @return \DateTime|null
     */
    public function getTurnedInDate(): ?\DateTime {
        return $this->turnedInDate;
    }

    /**
     * @param \DateTime|null $turnedInDate
     */
    public function setTurnedInDate(?\DateTime $turnedInDate): void {
        $this->turnedInDate = $turnedInDate;
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void {
        $this->description = $description;
    }

    /**
     * @return float|null
     */
    public function getGrade(): ?float {
        return $this->grade;
    }

    /**
     * @param float|null $grade
     */
    public function setGrade(?float $grade): void {
        $this->grade = $grade;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): void {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     */
    public function setUpdatedAt(?\DateTime $updatedAt): void {
        $this->updatedAt = $updatedAt;
    }
}
