<?php
declare(strict_types=1);

namespace App\Dto\Meeting\Input;
use App\Dto\Meeting\Contracts\AbstractCancellableMeetingInputDto;
use App\Dto\Traits\IsCompletedTrait;
use App\Dto\Traits\SchedulingTrait;
use Symfony\Component\Validator\Constraints as Assert;

class PatchGuidanceMeetingInputDto extends AbstractCancellableMeetingInputDto {
    #[Assert\Type(\DateTimeInterface::class)]
    private \DateTime $scheduledAt;

    #[Assert\GreaterThanOrEqual(0.5)]
    #[Assert\LessThanOrEqual(4)]
    private float $duration;

    use SchedulingTrait;

    use IsCompletedTrait;

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
