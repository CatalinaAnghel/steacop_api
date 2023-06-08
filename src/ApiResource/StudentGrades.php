<?php
declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
class StudentGrades
{
    /**
     * @var string $supervisorCode
     */
    private string $supervisorCode;

    /**
     * @var Grades[] $gradesCollection
     */
    private array $gradesCollection;

    /**
     * @return string
     */
    public function getSupervisorCode(): string
    {
        return $this->supervisorCode;
    }

    /**
     * @param string $supervisorCode
     * @return StudentGrades
     */
    public function setSupervisorCode(string $supervisorCode): StudentGrades
    {
        $this->supervisorCode = $supervisorCode;
        return $this;
    }

    /**
     * @return array
     */
    public function getGradesCollection(): array
    {
        return $this->gradesCollection;
    }

    /**
     * @param array $gradesCollection
     * @return StudentGrades
     */
    public function setGradesCollection(array $gradesCollection): StudentGrades
    {
        $this->gradesCollection = $gradesCollection;
        return $this;
    }
}
