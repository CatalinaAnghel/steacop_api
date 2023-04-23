<?php
declare(strict_types=1);

namespace App\Dto\Project\Output;

class ProjectInformationOutputDto
{
    /**
     * @var int $numberOfGuidanceMeetings
     */
    private int $numberOfGuidanceMeetings;

    /**
     * @var int $numberOfCompletedGuidanceMeetings
     */
    private int $numberOfCompletedGuidanceMeetings;

    /**
     * @var int $numberOfMilestoneMeetings
     */
    private int $numberOfMilestoneMeetings;

    /**
     * @var int $numberOfCompletedMilestoneMeetings
     */
    private int $numberOfCompletedMilestoneMeetings;

    /**
     * @var int $numberOfAssignments
     */
    private int $numberOfAssignments;

    /**
     * @var int $numberOfFinishedAssignments
     */
    private int $numberOfFinishedAssignments;

    /**
     * @var string|null $title
     */
    private ?string $title;

    /**
     * @var string|null $description
     */
    private ?string $description;

    /**
     * @var string|null $repositoryUrl
     */
    private ?string $repositoryUrl;

    public function __construct()
    {
        $this->numberOfGuidanceMeetings = 0;
        $this->numberOfAssignments = 0;
    }

    /**
     * @return int
     */
    public function getNumberOfGuidanceMeetings(): int
    {
        return $this->numberOfGuidanceMeetings;
    }

    /**
     * @param int $numberOfGuidanceMeetings
     */
    public function setNumberOfGuidanceMeetings(int $numberOfGuidanceMeetings): void
    {
        $this->numberOfGuidanceMeetings = $numberOfGuidanceMeetings;
    }

    /**
     * @return int
     */
    public function getNumberOfCompletedGuidanceMeetings(): int
    {
        return $this->numberOfCompletedGuidanceMeetings;
    }

    /**
     * @param int $numberOfCompletedGuidanceMeetings
     */
    public function setNumberOfCompletedGuidanceMeetings(int $numberOfCompletedGuidanceMeetings): void
    {
        $this->numberOfCompletedGuidanceMeetings = $numberOfCompletedGuidanceMeetings;
    }

    /**
     * @return int
     */
    public function getNumberOfMilestoneMeetings(): int
    {
        return $this->numberOfMilestoneMeetings;
    }

    /**
     * @param int $numberOfMilestoneMeetings
     */
    public function setNumberOfMilestoneMeetings(int $numberOfMilestoneMeetings): void
    {
        $this->numberOfMilestoneMeetings = $numberOfMilestoneMeetings;
    }

    /**
     * @return int
     */
    public function getNumberOfCompletedMilestoneMeetings(): int
    {
        return $this->numberOfCompletedMilestoneMeetings;
    }

    /**
     * @param int $numberOfCompletedMilestoneMeetings
     */
    public function setNumberOfCompletedMilestoneMeetings(int $numberOfCompletedMilestoneMeetings): void
    {
        $this->numberOfCompletedMilestoneMeetings = $numberOfCompletedMilestoneMeetings;
    }

    /**
     * @return int
     */
    public function getNumberOfAssignments(): int
    {
        return $this->numberOfAssignments;
    }

    /**
     * @param int $numberOfAssignments
     */
    public function setNumberOfAssignments(int $numberOfAssignments): void
    {
        $this->numberOfAssignments = $numberOfAssignments;
    }

    /**
     * @return int
     */
    public function getNumberOfFinishedAssignments(): int
    {
        return $this->numberOfFinishedAssignments;
    }

    /**
     * @param int $numberOfFinishedAssignments
     */
    public function setNumberOfFinishedAssignments(int $numberOfFinishedAssignments): void
    {
        $this->numberOfFinishedAssignments = $numberOfFinishedAssignments;
    }

    /**
     * @return ?string
     */
    public function getTitle(): string|null
    {
        return $this->title;
    }

    /**
     * @param ?string $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return ?string
     */
    public function getDescription(): string|null
    {
        return $this->description;
    }

    /**
     * @param ?string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return ?string
     */
    public function getRepositoryUrl(): string|null
    {
        return $this->repositoryUrl;
    }

    /**
     * @param ?string $repositoryUrl
     */
    public function setRepositoryUrl(?string $repositoryUrl): void
    {
        $this->repositoryUrl = $repositoryUrl;
    }
}
