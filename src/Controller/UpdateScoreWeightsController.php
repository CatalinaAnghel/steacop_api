<?php
declare(strict_types=1);

namespace App\Controller;

use App\Dto\ScoreWeight\Input\ScoreWeightDto;
use App\Dto\ScoreWeight\Output\ScoreWeightsDto;
use App\Service\Contract\ScoreWeightsServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class UpdateScoreWeightsController extends AbstractController
{
    public function __construct(private readonly ScoreWeightsServiceInterface $weightsService) {}

    /**
     * @param ScoreWeightDto $scoreWeightDto
     * @return ScoreWeightsDto
     */
    public function __invoke(ScoreWeightDto $scoreWeightDto): ScoreWeightsDto
    {
        return $this->weightsService->multipleUpdate($scoreWeightDto);
    }
}
