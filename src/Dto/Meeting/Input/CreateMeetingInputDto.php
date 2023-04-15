<?php
declare(strict_types=1);

namespace App\Dto\Meeting\Input;

use App\Dto\Meeting\Contracts\AbstractMeetingInputDto;
use App\Dto\Traits\SchedulingTrait;
use App\Validator\FutureDateTime;
use Symfony\Component\Validator\Constraints as Assert;

class CreateMeetingInputDto extends AbstractMeetingInputDto {
    #[Assert\Positive]
    private int $projectId;

    #[Assert\Type(\DateTimeInterface::class)]
    #[FutureDateTime]
    private \DateTime $scheduledAt;

    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0.5)]
    #[Assert\LessThanOrEqual(4)]
    private float $duration;

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

    /**
     * @return float
     */
    public function getDuration(): float {
        return $this->duration;
    }

    /**
     * @param float $duration
     */
    public function setDuration(float $duration): void {
        $this->duration = $duration;
    }
}
