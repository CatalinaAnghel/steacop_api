<?php
declare(strict_types=1);

namespace App\Dto\Rating\Input;

use Symfony\Component\Validator\Constraints as Assert;

class CreateRatingInputDto extends RatingInputDto
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    private int $guidanceMeetingId;

    /**
     * @return int
     */
    public function getGuidanceMeetingId(): int
    {
        return $this->guidanceMeetingId;
    }

    /**
     * @param int $guidanceMeetingId
     */
    public function setGuidanceMeetingId(int $guidanceMeetingId): void
    {
        $this->guidanceMeetingId = $guidanceMeetingId;
    }
}
