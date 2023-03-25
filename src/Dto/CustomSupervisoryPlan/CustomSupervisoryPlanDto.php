<?php
declare(strict_types=1);

namespace App\Dto\CustomSupervisoryPlan;

class CustomSupervisoryPlanDto {
    /**
     * @var int $numberOfAssignments
     */
    private int $numberOfAssignments;

    /**
     * @var int $numberOfGuidanceMeetings
     */
    private int $numberOfGuidanceMeetings;

    /**
     * @return int
     */
    public function getNumberOfAssignments(): int {
        return $this->numberOfAssignments;
    }

    /**
     * @param int $numberOfAssignments
     */
    public function setNumberOfAssignments(int $numberOfAssignments): void {
        $this->numberOfAssignments = $numberOfAssignments;
    }

    /**
     * @return int
     */
    public function getNumberOfGuidanceMeetings(): int {
        return $this->numberOfGuidanceMeetings;
    }

    /**
     * @param int $numberOfGuidanceMeetings
     */
    public function setNumberOfGuidanceMeetings(int $numberOfGuidanceMeetings): void {
        $this->numberOfGuidanceMeetings = $numberOfGuidanceMeetings;
    }
}
