<?php
declare(strict_types=1);

namespace App\Dto\ScoreWeight\Input;

class ScoreWeightDto
{
    /**
     * @var float $supportWeight
     */
    protected float $supportWeight;

    /**
     * @var float $structureWeight
     */
    protected float $structureWeight;

    /**
     * @var float float $supervisorRatingWeight
     */
    protected float $supervisorRatingWeight;

    /**
     * @return float
     */
    public function getSupportWeight(): float
    {
        return $this->supportWeight;
    }

    /**
     * @param float $supportWeight
     */
    public function setSupportWeight(float $supportWeight): void
    {
        $this->supportWeight = $supportWeight;
    }

    /**
     * @return float
     */
    public function getStructureWeight(): float
    {
        return $this->structureWeight;
    }

    /**
     * @param float $structureWeight
     */
    public function setStructureWeight(float $structureWeight): void
    {
        $this->structureWeight = $structureWeight;
    }

    /**
     * @return float
     */
    public function getSupervisorRatingWeight(): float
    {
        return $this->supervisorRatingWeight;
    }

    /**
     * @param float $supervisorRatingWeight
     */
    public function setSupervisorRatingWeight(float $supervisorRatingWeight): void
    {
        $this->supervisorRatingWeight = $supervisorRatingWeight;
    }
}
