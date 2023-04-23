<?php
declare(strict_types=1);

namespace App\Dto\Assignment\Input;

use App\Validator\FutureDateTime;
use Symfony\Component\Validator\Constraints as Assert;

class CreateAssignmentInputDto
{
    /**
     * @var int $projectId
     */
    #[Assert\NotBlank]
    #[Assert\Positive]
    private int $projectId;

    /**
     * @var \DateTime $dueDate
     */
    #[Assert\Type(\DateTimeInterface::class)]
    #[FutureDateTime]
    private \DateTime $dueDate;

    /**
     * @var string $title
     */
    #[Assert\NotBlank]
    #[Assert\Length(max: 128)]
    #[Assert\Length(min: 10)]
    private string $title;

    /**
     * @var string $description
     */
    #[Assert\NotBlank]
    #[Assert\Length(min: 16)]
    private string $description;

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
     * @return \DateTime
     */
    public function getDueDate(): \DateTime
    {
        return $this->dueDate;
    }

    /**
     * @param \DateTime $dueDate
     */
    public function setDueDate(\DateTime $dueDate): void
    {
        $this->dueDate = $dueDate;
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
}
