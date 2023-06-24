<?php
declare(strict_types=1);

namespace App\Service\ScoreCalculationStrategy;

use App\ApiResource\BaseScore;
use App\ApiResource\StructureScore;
use App\Entity\Assignment;
use App\Entity\MilestoneMeeting;
use App\Entity\Project;
use App\Entity\SystemSetting;
use App\Helper\SystemSettingsHelper;
use App\Repository\AssignmentRepository;
use App\Service\Contract\CalculationStrategyInterface;
use App\Service\Contract\MeetingScoreCalculatorTrait;
use Doctrine\ORM\EntityManagerInterface;

class StructureScoreStrategy implements CalculationStrategyInterface
{
    use MeetingScoreCalculatorTrait;

    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    /**
     * @inheritDoc
     */
    public function computeScore(Project $project): BaseScore
    {
        $milestoneMeetingMaxObject = $this->entityManager->getRepository(SystemSetting::class)
            ->findOneBy(['name' => SystemSettingsHelper::MilestoneMeetingsLimit->getName()]);
        $milestoneMeetingMax = null !== $milestoneMeetingMaxObject ? (int)$milestoneMeetingMaxObject->getValue() : 1;
        $meetingScore = $this->computeMeetingsScore(
            $project,
            MilestoneMeeting::class,
            $milestoneMeetingMax
        );
        $assignmentScore = $this->computeAssignmentScore($project);

        $score = new StructureScore();
        $score->setAssignmentsScore($assignmentScore)
            ->setMilestoneMeetingsScore($meetingScore)
            ->setTotalScore(($meetingScore + $assignmentScore) / 2);

        return $score;
    }

    /**
     * Compute the score for the assignments
     * @param Project $project
     * @return float
     */
    private function computeAssignmentScore(Project $project): float
    {
        $score = 0.0;
        $latePenaltyValue = $this->entityManager->getRepository(SystemSetting::class)
            ->findOneBy(['name' => SystemSettingsHelper::AssignmentPenalization->getName()]);
        $latePenalty = null !== $latePenaltyValue ? (float)$latePenaltyValue->getValue() : 0.0;
        $assignments = $project->getAssignments()->filter(function(Assignment $assignment){
            return $assignment->getDueDate() <= new \DateTime('Now');
        });
        if (count($assignments)) {
            $assignmentSum = 0.0;
            foreach ($assignments as $assignment) {
                if (null !== $assignment->getTurnedInDate() &&
                    $assignment->getTurnedInDate() <= $assignment->getDueDate()) {
                    // turned in on time
                    $assignmentSum += 100;
                } elseif (null !== $assignment->getTurnedInDate()) {
                    $numberOfDaysFromDueDate = $assignment->getTurnedInDate()->diff($assignment->getDueDate())->days?? 1;
                    $partialScore = (float)$latePenalty * $numberOfDaysFromDueDate;
                    if($partialScore < 100){
                        $assignmentSum += 100 - $partialScore;
                    }
                }
            }
            
            /**
             * @var AssignmentRepository $assignmentRepo
             */
            $assignmentRepo = $this->entityManager->getRepository(Assignment::class);
            $numberOfPassedAssignments = $assignmentRepo->countPassedAssignments($project->getId());
            $score = $assignmentSum / $numberOfPassedAssignments;
        }

        return $score;
    }
}
