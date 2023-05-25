<?php
declare(strict_types=1);

namespace App\Dto\Traits;

trait IsMissedTrait
{
    private bool $isMissed;

    /**
     * @return bool
     */
    public function getIsMissed(): bool
    {
        return $this->isMissed;
    }

    /**
     * @param bool $isMissed
     */
    public function setIsMissed(bool $isMissed): void
    {
        $this->isMissed = $isMissed;
    }
}
