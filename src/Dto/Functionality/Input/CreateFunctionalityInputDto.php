<?php
declare(strict_types=1);

namespace App\Dto\Functionality\Input;

class CreateFunctionalityInputDto
{
    /**
     * @var int $code
     */
    private int $projectId;

    /**
     * @var string|null $parentFunctionalityCode
     */
    private ?string $parentFunctionalityCode;

    /**
     * @var string $type
     */
    private string $type;

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
     * @return string|null
     */
    public function getParentFunctionalityCode(): ?string
    {
        return $this->parentFunctionalityCode;
    }

    /**
     * @param string|null $parentFunctionalityCode
     */
    public function setParentFunctionalityCode(?string $parentFunctionalityCode): void
    {
        $this->parentFunctionalityCode = $parentFunctionalityCode;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
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
}
