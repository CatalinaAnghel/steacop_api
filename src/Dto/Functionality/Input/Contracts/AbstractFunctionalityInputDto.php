<?php
declare(strict_types=1);

namespace App\Dto\Functionality\Input\Contracts;

abstract class AbstractFunctionalityInputDto
{
    /**
     * @var int|null $parentFunctionalityId
     */
    private ?int $parentFunctionalityId = null;

    /**
     * @var int $type
     */
    private int $type;

    /**
     * @var string $title
     */
    private string $title;

    /**
     * @var \DateTime|null $dueDate
     */
    private ?\DateTime $dueDate;

    /**
     * @var string|null $description
     */
    private ?string $description;

    /**
     * @return int|null
     */
    public function getParentFunctionalityId(): ?int
    {
        return $this->parentFunctionalityId;
    }

    /**
     * @param int|null $parentFunctionalityId
     */
    public function setParentFunctionalityId(?int $parentFunctionalityId): void
    {
        $this->parentFunctionalityId = $parentFunctionalityId;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
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
}
