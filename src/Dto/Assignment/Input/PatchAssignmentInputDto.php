<?php
declare(strict_types=1);

namespace App\Dto\Assignment\Input;

use Symfony\Component\Validator\Constraints as Assert;

class PatchAssignmentInputDto
{
    /**
     * @var ?\DateTime $dueDate
     */
    #[Assert\Type(\DateTimeInterface::class)]
    private ?\DateTime $dueDate;

    /**
     * @var ?string $title
     */
    #[Assert\Length(max: 128)]
    #[Assert\Length(min: 10)]
    private ?string $title;

    /**
     * @var ?string $description
     */
    #[Assert\Length(min: 16)]
    private ?string $description;

    /**
     * @var float|null $grade
     */
    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\LessThanOrEqual(10)]
    private ?float $grade;

    /**
     * @var bool
     */
    private bool $isTurnedIn = false;

    /**
     * @return ?\DateTime
     */
    public function getDueDate(): ?\DateTime
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
     * @return ?string
     */
    public function getTitle(): ?string
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
     * @return ?string
     */
    public function getDescription(): ?string
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
     * @return ?float
     */
    public function getGrade(): ?float
    {
        return $this->grade;
    }

    /**
     * @param float $grade
     */
    public function setGrade(float $grade): void
    {
        $this->grade = $grade;
    }

    /**
     * @return bool
     */
    public function isTurnedIn(): bool
    {
        return $this->isTurnedIn;
    }

    /**
     * @param bool $isTurnedIn
     */
    public function setIsTurnedIn(bool $isTurnedIn): void
    {
        $this->isTurnedIn = $isTurnedIn;
    }
}
