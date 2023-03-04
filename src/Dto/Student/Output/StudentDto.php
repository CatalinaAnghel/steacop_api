<?php
declare(strict_types=1);

namespace App\Dto\Student\Output;

use App\Dto\Project\Output\ProjectDto;
use App\Dto\Shared\Output\AbstractPerson;
use App\Dto\Shared\Output\SpecializationDto;

final class StudentDto extends AbstractPerson {
    /**
     * @var ProjectDto $project
     */
    private ProjectDto $project;

    /**
     * @var SpecializationDto $specialization
     */
    private SpecializationDto $specialization;

    /**
     * @return ProjectDto
     */
    public function getProject(): ProjectDto {
        return $this->project;
    }

    /**
     * @param ProjectDto $project
     */
    public function setProject(ProjectDto $project): void {
        $this->project = $project;
    }

    /**
     * @return SpecializationDto
     */
    public function getSpecialization(): SpecializationDto {
        return $this->specialization;
    }

    /**
     * @param SpecializationDto $specialization
     */
    public function setSpecialization(SpecializationDto $specialization): void {
        $this->specialization = $specialization;
    }
}
