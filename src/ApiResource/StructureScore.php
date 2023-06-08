<?php
declare(strict_types=1);

namespace App\ApiResource;

class StructureScore extends BaseScore
{
    /**
     * @var float $assignmentsScore
     */
    private float $assignmentsScore;

    /**
     * @var float $milestoneMeetingsScore
     */
    private float $milestoneMeetingsScore;

    /**
     * @return float
     */
    public function getAssignmentsScore(): float
    {
        return $this->assignmentsScore;
    }

    /**
     * @param float $assignmentsScore
     * @return StructureScore
     */
    public function setAssignmentsScore(float $assignmentsScore): self
    {
        $this->assignmentsScore = $assignmentsScore;

        return $this;
    }

    /**
     * @return float
     */
    public function getMilestoneMeetingsScore(): float
    {
        return $this->milestoneMeetingsScore;
    }

    /**
     * @param float $milestoneMeetingsScore
     * @return StructureScore
     */
    public function setMilestoneMeetingsScore(float $milestoneMeetingsScore): self
    {
        $this->milestoneMeetingsScore = $milestoneMeetingsScore;

        return $this;
    }
}
