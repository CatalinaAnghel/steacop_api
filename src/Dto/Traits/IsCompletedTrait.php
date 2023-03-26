<?php
declare(strict_types=1);

namespace App\Dto\Traits;

trait IsCompletedTrait {
    private bool $isCompleted;

    /**
     * @return bool
     */
    public function getIsCompleted(): bool {
        return $this->isCompleted;
    }

    /**
     * @param bool $isCompleted
     */
    public function setIsCompleted(bool $isCompleted): void {
        $this->isCompleted = $isCompleted;
    }
}
