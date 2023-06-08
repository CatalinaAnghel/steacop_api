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
        return (new BaseScore())->setTotalScore(
            $this->computeMeetingsScore(
                $project,
                GuidanceMeeting::class,
                count($project->getGuidanceMeetings())
            )
        );
    }
}
