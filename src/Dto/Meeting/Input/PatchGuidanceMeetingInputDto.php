<?php
declare(strict_types=1);

namespace App\Dto\Meeting\Input;
use App\Dto\Meeting\Contracts\AbstractMeetingInputDto;
use App\Dto\Traits\SchedulingTrait;
use Symfony\Component\Validator\Constraints as Assert;

class PatchGuidanceMeetingInputDto extends AbstractMeetingInputDto{
    #[Assert\Type(\DateTimeInterface::class)]
    private \DateTime $scheduledAt;

    use SchedulingTrait;
}
