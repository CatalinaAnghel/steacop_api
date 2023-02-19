<?php
declare(strict_types=1);

namespace App\Dto\ScoreWeight\Input;

class ScoreWeightDto {
    /**
     * @var float $supportWeight
     */
    public float $supportWeight;

    /**
     * @var float $structureWeight
     */
    public float $structureWeight;

    /**
     * @var float $structurePenalty
     */
    public float $structurePenalty;

    /**
     * @var float $ratingWeight
     */
    public float $ratingWeight;

    /**
     * @return float
     */
    public function getSupportWeight(): float {
        return $this->supportWeight;
    }

    /**
     * @param float $supportWeight
     */
    public function setSupportWeight(float $supportWeight): void {
        $this->supportWeight = $supportWeight;
    }

    /**
     * @return float
     */
    public function getStructureWeight(): float {
        return $this->structureWeight;
    }

    /**
     * @param float $structureWeight
     */
    public function setStructureWeight(float $structureWeight): void {
        $this->structureWeight = $structureWeight;
    }

    /**
     * @return float
     */
    public function getStructurePenalty(): float {
        return $this->structurePenalty;
    }

    /**
     * @param float $structurePenalty
     */
    public function setStructurePenalty(float $structurePenalty): void {
        $this->structurePenalty = $structurePenalty;
    }

    /**
     * @return float
     */
    public function getRatingWeight(): float {
        return $this->ratingWeight;
    }

    /**
     * @param float $ratingWeight
     */
    public function setRatingWeight(float $ratingWeight): void {
        $this->ratingWeight = $ratingWeight;
    }
}
