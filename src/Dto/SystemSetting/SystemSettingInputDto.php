<?php
declare(strict_types=1);

namespace App\Dto\SystemSetting;

class SystemSettingInputDto
{
    private int $milestoneMeetingsLimit;
    private float $assignmentPenalization;

    /**
     * @return int
     */
    public function getMilestoneMeetingsLimit(): int
    {
        return $this->milestoneMeetingsLimit;
    }

    /**
     * @param int $milestoneMeetingsLimit
     */
    public function setMilestoneMeetingsLimit(int $milestoneMeetingsLimit): void
    {
        $this->milestoneMeetingsLimit = $milestoneMeetingsLimit;
    }

    /**
     * @return float
     */
    public function getAssignmentPenalization(): float
    {
        return $this->assignmentPenalization;
    }

    /**
     * @param float $assignmentPenalization
     */
    public function setAssignmentPenalization(float $assignmentPenalization): void
    {
        $this->assignmentPenalization = $assignmentPenalization;
    }
}
