<?php
declare(strict_types=1);

namespace App\Helper;

enum WeightsHelper
{
    case RatingWeight;
    case SupportWeight;
    case StructureWeight;
    case StructurePenalty;

    /**
     * Get the weight name
     * @return string
     */
    public function getWeightName(): string
    {
        return $this->name;
    }

    /**
     * Get the weight value
     * @return float
     */
    public function getWeightValue(): float
    {
        return match ($this) {
            self::StructureWeight, self::SupportWeight => 40.0,
            self::RatingWeight                         => 20.0,
            self::StructurePenalty                     => 5.5
        };
    }
}
