<?php
declare(strict_types=1);

namespace App\Service\Contract;

use App\Dto\ScoreWeight\Input\ScoreWeightDto;

interface ScoreWeightsServiceInterface {
    /**
     * @param ScoreWeightDto $scoreWeightDto
     * @return \App\Dto\ScoreWeight\Output\ScoreWeightsDto
     */
    public function multipleUpdate(ScoreWeightDto $scoreWeightDto): \App\Dto\ScoreWeight\Output\ScoreWeightsDto;
}
