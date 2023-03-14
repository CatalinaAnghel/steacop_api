<?php
declare(strict_types=1);

namespace App\Dto\CustomSupervisoryPlan\Contracts;

abstract class AbstractCustomSupervisoryPlanDto {
    /**
     * @var int $numberOfAssignments
     */
    protected int $numberOfAssignments;

    /**
     * @var int $numberOfGuidanceMeetings
     */
    protected int $numberOfGuidanceMeetings;

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
