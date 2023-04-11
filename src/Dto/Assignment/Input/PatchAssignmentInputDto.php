<?php
declare(strict_types=1);

namespace App\Dto\Assignment\Input;
use App\Validator\FutureDateTime;
use Symfony\Component\Validator\Constraints as Assert;

class PatchAssignmentInputDto {
    /**
     * @var \DateTime $dueDate
     */
    #[Assert\Type(\DateTimeInterface::class)]
    #[FutureDateTime]
    private \DateTime $dueDate;

    /**
     * @var string $title
     */
    #[Assert\Length(max: 128)]
    private string $title;

    /**
     * @var string $description
     */
    #[Assert\Length(max: 255)]
    private string $description;

    /**
     * @var float $grade
     */
    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\LessThanOrEqual(10)]
    private float $grade;

    /**
     * @var \DateTime
     */
    #[Assert\Type(\DateTimeInterface::class)]
    private \DateTime $turnedInDate;

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
     * @return float
     */
    public function getGrade(): float {
        return $this->grade;
    }

    /**
     * @param float $grade
     */
    public function setGrade(float $grade): void {
        $this->grade = $grade;
    }

    /**
     * @return \DateTime
     */
    public function getTurnedInDate(): \DateTime {
        return $this->turnedInDate;
    }

    /**
     * @param \DateTime $turnedInDate
     */
    public function setTurnedInDate(\DateTime $turnedInDate): void {
        $this->turnedInDate = $turnedInDate;
    }
}
