<?php
declare(strict_types=1);

namespace App\Dto\ScoreWeight\Output;

class ScoreWeightsDto {
    /**
     * @var array<ScoreWeight> $weights
     */
    public array $weights;

    public function __construct() {
        $this->weights = [];
    }

    /**
     * @return array
     */
    public function getWeights(): array {
        return $this->weights;
    }

    /**
     * @param ScoreWeight $weight
     * @return void
     */
    public function addWeight(ScoreWeight $weight): void {
        $this->weights[] = $weight;
    }
}
