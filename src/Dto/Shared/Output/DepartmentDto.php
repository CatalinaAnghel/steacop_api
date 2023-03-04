<?php
declare(strict_types=1);

namespace App\Dto\Shared\Output;

use App\Dto\Traits\IdentityTrait;

class DepartmentDto {
    use IdentityTrait;

    /**
     * @var string $name
     */
    private string $name;

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
}
