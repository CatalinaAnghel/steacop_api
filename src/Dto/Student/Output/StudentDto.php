<?php
declare(strict_types=1);

namespace App\Dto\Student\Output;

use App\Dto\Project\Output\ProjectDto;
use App\Dto\Traits\IdentityTrait;
use App\Dto\Traits\UserTrait;

final class StudentDto {
    use IdentityTrait;

    /**
     * @var string $firstName
     */
    private string $firstName;

    /**
     * @var string $lastName
     */
    private string $lastName;

    /**
     * @var string $phoneNumber
     */
    private string $phoneNumber;

    private ProjectDto $project;

    /**
     * @var SpecializationDto $specialization
     */
    private SpecializationDto $specialization;

    use UserTrait;

    /**
     * @return string
     */
    public function getFirstName(): string {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void {
        $this->phoneNumber = $phoneNumber;
    }

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
