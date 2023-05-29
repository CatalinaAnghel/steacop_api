<?php
declare(strict_types=1);

namespace App\Dto\Project\Output;

class ProjectInformationOutputDto
{
    /**
     * @var MeetingInformation $milestoneMeetingInformation
     */
    private MeetingInformation $milestoneMeetingInformation;

    /**
     * @var MeetingInformation $guidanceMeetingInformation
     */
    private MeetingInformation $guidanceMeetingInformation;

    /**
     * @var AssignmentInformation $assignmentInformation
     */
    private AssignmentInformation $assignmentInformation;

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

    /**
     * @var float|null $grade
     */
    private ?float $grade;

    /**
     * @return MeetingInformation
     */
    public function getMilestoneMeetingInformation(): MeetingInformation
    {
        return $this->milestoneMeetingInformation;
    }

    /**
     * @param MeetingInformation $milestoneMeetingInformation
     */
    public function setMilestoneMeetingInformation(MeetingInformation $milestoneMeetingInformation): void
    {
        $this->milestoneMeetingInformation = $milestoneMeetingInformation;
    }

    /**
     * @return MeetingInformation
     */
    public function getGuidanceMeetingInformation(): MeetingInformation
    {
        return $this->guidanceMeetingInformation;
    }

    /**
     * @param MeetingInformation $guidanceMeetingInformation
     */
    public function setGuidanceMeetingInformation(MeetingInformation $guidanceMeetingInformation): void
    {
        $this->guidanceMeetingInformation = $guidanceMeetingInformation;
    }

    /**
     * @return AssignmentInformation
     */
    public function getAssignmentInformation(): AssignmentInformation
    {
        return $this->assignmentInformation;
    }

    /**
     * @param AssignmentInformation $assignmentInformation
     */
    public function setAssignmentInformation(AssignmentInformation $assignmentInformation): void
    {
        $this->assignmentInformation = $assignmentInformation;
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

    /**
     * @return float|null
     */
    public function getGrade(): ?float
    {
        return $this->grade;
    }

    /**
     * @param float|null $grade
     */
    public function setGrade(?float $grade): void
    {
        $this->grade = $grade;
    }
}
