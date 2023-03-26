<?php
declare(strict_types=1);

namespace App\Dto\Meeting\Output;

use App\Dto\Meeting\Contracts\AbstractMeetingOutputDto;

class MilestoneMeetingOutputDto extends AbstractMeetingOutputDto {
    private ?float $grade;

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
