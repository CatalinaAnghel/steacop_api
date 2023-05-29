<?php
declare(strict_types=1);

namespace App\Dto\Traits;

trait BasicStatisticsTrait
{
    /**
     * @var int $total
     */
    private int $total;

    /**
     * @var int $completed
     */
    private int $completed;

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getCompleted(): int
    {
        return $this->completed;
    }

    /**
     * @param int $completed
     */
    public function setCompleted(int $completed): void
    {
        $this->completed = $completed;
    }
}
