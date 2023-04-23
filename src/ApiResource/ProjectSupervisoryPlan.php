<?php
declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
class ProjectSupervisoryPlan
{
    /**
     * @var int $projectId
     */
    protected int $projectId;

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
    public function getProjectId(): int
    {
        return $this->projectId;
    }

    /**
     * @param int $projectId
     */
    public function setProjectId(int $projectId): void
    {
        $this->projectId = $projectId;
    }

    /**
     * @return int
     */
    public function getNumberOfAssignments(): int
    {
        return $this->numberOfAssignments;
    }

    /**
     * @param int $numberOfAssignments
     */
    public function setNumberOfAssignments(int $numberOfAssignments): void
    {
        $this->numberOfAssignments = $numberOfAssignments;
    }

    /**
     * @return int
     */
    public function getNumberOfGuidanceMeetings(): int
    {
        return $this->numberOfGuidanceMeetings;
    }

    /**
     * @param int $numberOfGuidanceMeetings
     */
    public function setNumberOfGuidanceMeetings(int $numberOfGuidanceMeetings): void
    {
        $this->numberOfGuidanceMeetings = $numberOfGuidanceMeetings;
    }
}
