<?php
declare(strict_types=1);

namespace App\Dto\Traits;

trait IdentityTrait {
    /**
     * @var int $id
     */
    private int $id;

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }
}
