<?php
declare(strict_types=1);

namespace App\Service\Contract;

use App\ApiResource\BaseScore;
use App\Entity\Project;

interface CalculationStrategyInterface
{
    /**
     * @param Project $project
     * @return BaseScore
     */
    public function computeScore(Project $project): BaseScore;
}
