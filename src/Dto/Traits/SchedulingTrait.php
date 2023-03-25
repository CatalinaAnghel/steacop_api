<?php
declare(strict_types=1);

namespace App\Dto\Traits;

trait SchedulingTrait {
    /**
     * @return \DateTime
     */
    public function getScheduledAt(): \DateTime {
        return $this->scheduledAt;
    }

    /**
     * @param \DateTime $scheduledAt
     */
    public function setScheduledAt(\DateTime $scheduledAt): void {
        $this->scheduledAt = $scheduledAt;
    }
}
