<?php
declare(strict_types=1);

namespace App\Service\Contract;

use App\Entity\Project;

trait MeetingScoreCalculatorTrait
{

    /**
     * Computes the score for the meetings
     * @param Project $project
     * @param string $meetingType
     * @return float
     */
    private function computeMeetingsScore(Project $project, string $meetingType, int $totalNumberOfMeetings): float
    {
        $completedMeetings = $this->entityManager->getRepository($meetingType)->count([
            'isCompleted' => true,
            'project' => $project
        ]);

        if($completedMeetings > $totalNumberOfMeetings){
            return 100;
        }

        return $completedMeetings / $totalNumberOfMeetings * 100;
    }
}
