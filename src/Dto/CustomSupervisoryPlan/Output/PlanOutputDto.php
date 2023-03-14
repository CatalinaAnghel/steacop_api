<?php
declare(strict_types=1);

namespace App\Dto\CustomSupervisoryPlan\Output;

use App\Dto\CustomSupervisoryPlan\Contracts\AbstractCustomSupervisoryPlanDto;

class PlanOutputDto extends AbstractCustomSupervisoryPlanDto{
    /**
     * @var bool $isSuggested
     */
    protected bool $suggested;

    public function __construct() {
        $this->suggested = false;
    }

    /**
     * @return bool
     */
    public function isSuggested(): bool {
        return $this->suggested;
    }

    /**
     * @param bool $isSuggested
     */
    public function setSuggested(bool $isSuggested): void {
        $this->suggested = $isSuggested;
    }
}
