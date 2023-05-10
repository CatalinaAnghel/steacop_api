<?php
declare(strict_types=1);

namespace App\Dto\Functionality\Output;

use App\Dto\Project\Output\ProjectDto;

class FunctionalityOutputDto
{
    private string $code;

    private ProjectDto $project;

    private string $title;

    private ?string $description;

    private ?\DateTime $dueDate;

    private FunctionalityCharacteristicOutputDto $status;

    private FunctionalityCharacteristicOutputDto $type;

    private ?self $parent;

    private ?\DateTimeImmutable $createdAt;

    private ?\DateTimeInterface $updatedAt;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return ProjectDto
     */
    public function getProject(): ProjectDto
    {
        return $this->project;
    }

    /**
     * @param ProjectDto $project
     */
    public function setProject(ProjectDto $project): void
    {
        $this->project = $project;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return \DateTime|null
     */
    public function getDueDate(): ?\DateTime
    {
        return $this->dueDate;
    }

    /**
     * @param \DateTime|null $dueDate
     */
    public function setDueDate(?\DateTime $dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    /**
     * @return FunctionalityCharacteristicOutputDto
     */
    public function getStatus(): FunctionalityCharacteristicOutputDto
    {
        return $this->status;
    }

    /**
     * @param FunctionalityCharacteristicOutputDto $status
     */
    public function setStatus(FunctionalityCharacteristicOutputDto $status): void
    {
        $this->status = $status;
    }

    /**
     * @return FunctionalityCharacteristicOutputDto
     */
    public function getType(): FunctionalityCharacteristicOutputDto
    {
        return $this->type;
    }

    /**
     * @param FunctionalityCharacteristicOutputDto $type
     */
    public function setType(FunctionalityCharacteristicOutputDto $type): void
    {
        $this->type = $type;
    }

    /**
     * @return FunctionalityOutputDto|null
     */
    public function getParent(): ?FunctionalityOutputDto
    {
        return $this->parent;
    }

    /**
     * @param FunctionalityOutputDto|null $parent
     */
    public function setParent(?FunctionalityOutputDto $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable|null $createdAt
     */
    public function setCreatedAt(?\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface|null $updatedAt
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}