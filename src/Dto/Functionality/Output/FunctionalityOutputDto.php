<?php
declare(strict_types=1);

namespace App\Dto\Functionality\Output;

use App\Dto\Traits\IdentityTrait;

class FunctionalityOutputDto extends BaseFunctionalityOutputDto
{
    private int $projectId;

    private ?string $description;

    private ?\DateTime $dueDate;

    private FunctionalityCharacteristicOutputDto $status;

    private FunctionalityCharacteristicOutputDto $type;

    private ?BaseFunctionalityOutputDto $parent;

    private ?\DateTimeImmutable $createdAt;

    private ?\DateTimeInterface $updatedAt;

    private ?int $orderNumber;

    /**
     * @return int
     */
    public function getProjectId(): int
    {
        return $this->projectId;
    }

    /**
     * @param int $projectId
     */
    public function setProjectId(int $projectId): void
    {
        $this->projectId = $projectId;
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
     * @return BaseFunctionalityOutputDto|null
     */
    public function getParent(): ?BaseFunctionalityOutputDto
    {
        return $this->parent;
    }

    /**
     * @param BaseFunctionalityOutputDto|null $parent
     */
    public function setParent(?BaseFunctionalityOutputDto $parent): void
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

    /**
     * @return ?int
     */
    public function getOrderNumber(): ?int
    {
        return $this->orderNumber;
    }

    /**
     * @param ?int $orderNumber
     */
    public function setOrderNumber(?int $orderNumber): void
    {
        $this->orderNumber = $orderNumber;
    }
}
