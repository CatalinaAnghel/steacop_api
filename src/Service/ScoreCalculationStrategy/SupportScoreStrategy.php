<?php
declare(strict_types=1);

namespace App\Service\ScoreCalculationStrategy;

use App\ApiResource\BaseScore;
use App\Entity\GuidanceMeeting;
use App\Entity\Project;
use App\Service\Contract\CalculationStrategyInterface;
use App\Service\Contract\MeetingScoreCalculatorTrait;
use Doctrine\ORM\EntityManagerInterface;

class SupportScoreStrategy implements CalculationStrategyInterface
{
    use MeetingScoreCalculatorTrait;

    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    /**
     * @inheritDoc
     */
    public function computeScore(Project $project): BaseScore
    {
        if(null !== $project->getSupervisoryPlan()){
            $totalNumberOfMeetings = $project->getSupervisoryPlan()?->getNumberOfGuidanceMeetings();
        } else {
            $studentPlan = $project->getStudent()->getSupervisoryPlan();
            $supervisorPlan = $project->getSupervisor()->getSupervisoryPlan();
            $totalNumberOfMeetings = ($studentPlan->getNumberOfGuidanceMeetings() +
                    $supervisorPlan->getNumberOfGuidanceMeetings()) / 2;
        }

        return (new BaseScore())->setTotalScore(
            $this->computeMeetingsScore(
                $project,
                GuidanceMeeting::class,
                isset($totalNumberOfMeetings) && $totalNumberOfMeetings > 0? $totalNumberOfMeetings: 1
            )
        );
    }
}
