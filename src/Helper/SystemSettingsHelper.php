<?php
declare(strict_types=1);

namespace App\Helper;

enum SystemSettingsHelper
{
    case MilestoneMeetingsLimit;
    case AssignmentPenalization;

    /**
     * Get the weight name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the weight value
     * @return float
     */
    public function getValue(): float
    {
        return match ($this) {
            self::MilestoneMeetingsLimit => 6,
            self::AssignmentPenalization => 5.5
        };
    }
}
