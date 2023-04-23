<?php
declare(strict_types=1);

namespace App\Dto\Traits;

use App\Dto\Shared\Output\DepartmentDto;

trait DepartmentTrait
{
    /**
     * @var DepartmentDto $department
     */
    private DepartmentDto $department;

    /**
     * @return DepartmentDto
     */
    public function getDepartment(): DepartmentDto
    {
        return $this->department;
    }

    /**
     * @param DepartmentDto $department
     */
    public function setDepartment(DepartmentDto $department): void
    {
        $this->department = $department;
    }
}
