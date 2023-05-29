<?php
declare(strict_types=1);

namespace App\Dto\Project\Output;

use App\Dto\Traits\BasicStatisticsTrait;

class MeetingInformation
{
    use BasicStatisticsTrait;

    /**
     * @var int $missed
     */
    private int $missed;

    /**
     * @return int
     */
    public function getMissed(): int
    {
        return $this->missed;
    }

    /**
     * @param int $missed
     */
    public function setMissed(int $missed): void
    {
        $this->missed = $missed;
    }
}
