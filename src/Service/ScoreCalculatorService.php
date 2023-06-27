<?php
declare(strict_types=1);

namespace App\Service;

use App\ApiResource\BaseScore;
use App\Entity\Project;
use App\Service\Contract\CalculationStrategyInterface;

class ScoreCalculatorService
{
    public function __construct(private CalculationStrategyInterface $strategy)
    {
    }

    public function setStrategy(CalculationStrategyInterface $calculationStrategy): void
    {
        $this->strategy = $calculationStrategy;
    }

    public function computeScore(Project $project): BaseScore
    {
        return $this->strategy->computeScore($project);
    }
}