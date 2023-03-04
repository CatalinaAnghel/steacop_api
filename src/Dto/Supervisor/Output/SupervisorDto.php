<?php
declare(strict_types=1);

namespace App\Dto\Supervisor\Output;

use App\Dto\Shared\Output\AbstractPerson;
use App\Dto\Traits\DepartmentTrait;

class SupervisorDto extends AbstractPerson {
    use DepartmentTrait;
}
