<?php
declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
class Rating {
    #[ApiProperty(identifier: true)]
    private int $meetingId;

    /**
     * @return int
     */
    public function getMeetingId(): int {
        return $this->meetingId;
    }

    /**
     * @param int $meetingId
     */
    public function setMeetingId(int $meetingId): void {
        $this->meetingId = $meetingId;
    }
}
