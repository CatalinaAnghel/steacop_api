<?php
declare(strict_types=1);

namespace App\Dto\Student\Output;

use App\Dto\Traits\IdentityTrait;

class SpecializationDto {
    use IdentityTrait;

    /**
     * @var string $name
     */
    private string $name;

    /**
     * @var DepartmentDto $department
     */
    private DepartmentDto $department;

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return DepartmentDto
     */
    public function getDepartment(): DepartmentDto {
        return $this->department;
    }

    /**
     * @param DepartmentDto $department
     */
    public function setDepartment(DepartmentDto $department): void {
        $this->department = $department;
    }
}
