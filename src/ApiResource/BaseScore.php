<?php
declare(strict_types=1);

namespace App\ApiResource;

class BaseScore
{
    private float $totalScore;

    /**
     * @return float
     */
    public function getTotalScore(): float
    {
        return $this->totalScore;
    }

    /**
     * @param float $totalScore
     * @return BaseScore
     */
    public function setTotalScore(float $totalScore): self
    {
        $this->totalScore = $totalScore;

        return $this;
    }
}
