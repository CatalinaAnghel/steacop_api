<?php
declare(strict_types=1);

namespace App\Dto\Traits;

use DateTimeImmutable;

trait IsCanceledTrait
{
    private bool $isCanceled;
    private ?DateTimeImmutable $canceledAt;

    /**
     * @return bool
     */
    public function getIsCanceled(): bool
    {
        return $this->isCanceled;
    }

    /**
     * @param bool $isCanceled
     */
    public function setIsCanceled(bool $isCanceled): void
    {
        $this->isCanceled = $isCanceled;
    }

    /**
     * @return ?DateTimeImmutable
     */
    public function getCanceledAt(): ?DateTimeImmutable
    {
        return $this->canceledAt;
    }

    /**
     * @param DateTimeImmutable $canceledAt
     */
    public function setCanceledAt(DateTimeImmutable $canceledAt): void
    {
        $this->canceledAt = $canceledAt;
    }
}
