<?php
declare(strict_types=1);

namespace App\Dto\Meeting\Input;

use App\Dto\Meeting\Contracts\AbstractMeetingInputDto;
use App\Dto\Traits\IsCompletedTrait;
use App\Dto\Traits\SchedulingTrait;
use Symfony\Component\Validator\Constraints as Assert;

class PatchMilestoneMeetingInputDto extends AbstractMeetingInputDto {
    #[Assert\Type(\DateTimeInterface::class)]
    private \DateTime $scheduledAt;

    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\LessThanOrEqual(10)]
    private ?float $grade;

    use SchedulingTrait;

    use IsCompletedTrait;

    /**
     * @return float|null
     */
    public function getGrade(): ?float {
        return $this->grade;
    }

    /**
     * @param float|null $grade
     */
    public function setGrade(?float $grade): void {
        $this->grade = $grade;
    }
}
