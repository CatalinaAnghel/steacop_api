<?php
declare(strict_types=1);

namespace App\Dto\Meeting\Input;

use App\Dto\Meeting\Contracts\AbstractMeetingInputDto;
use App\Dto\Traits\SchedulingTrait;
use App\Validator\FutureDateTime;
use Symfony\Component\Validator\Constraints as Assert;

class CreateMilestoneMeetingInputDto extends AbstractMeetingInputDto {
    #[Assert\Positive]
    private int $projectId;

    #[Assert\Type(\DateTimeInterface::class)]
    #[FutureDateTime]
    private \DateTime $scheduledAt;

    use SchedulingTrait;

    /**
     * @return int
     */
    public function getProjectId(): int {
        return $this->projectId;
    }

    /**
     * @param int $projectId
     */
    public function setProjectId(int $projectId): void {
        $this->projectId = $projectId;
    }
}
