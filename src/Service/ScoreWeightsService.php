<?php
declare(strict_types=1);

namespace App\Service;

use App\Dto\ScoreWeight\Input\ScoreWeightDto;
use App\Dto\ScoreWeight\Output\ScoreWeight as ScoreWeightOutputDto;
use App\Dto\ScoreWeight\Output\ScoreWeightsDto;
use App\Entity\ScoreWeight;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Doctrine\ORM\EntityManagerInterface;

class ScoreWeightsService implements Contract\ScoreWeightsServiceInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    /**
     * @inheritDoc
     */
    public function multipleUpdate(ScoreWeightDto $scoreWeightDto): ScoreWeightsDto
    {
        $repository = $this->entityManager->getRepository(ScoreWeight::class);
        $weights = $repository->findAll();
        $weightsOutputDto = new ScoreWeightsDto();
        $total = 0.0;
        $ratingScore = null;
        foreach ($weights as $weight) {
            $methodName = 'get' . lcfirst($weight->getName());
            if (method_exists($scoreWeightDto, $methodName)) {
                $weight->setWeight($scoreWeightDto->$methodName());
                if($scoreWeightDto->$methodName() !== 'SupervisorRatingWeight'){
                    $total += $scoreWeightDto->$methodName();
                }
                $repository->add($weight, true);
                $weightOutputDto = $this->mapWeightToOutputDto($weight);
                $weightsOutputDto->addWeight($weightOutputDto);
            }
            if ($weight->getName() === 'RatingWeight') {
                $ratingScore = $weight;
            }
        }

        if ($total >= 0) {
            $ratingScore->setWeight(100 - $total);
            $repository->add($ratingScore, true);
            $weightOutputDto = $this->mapWeightToOutputDto($ratingScore);
            $weightsOutputDto->addWeight($weightOutputDto);
        }

        return $weightsOutputDto;
    }

    private function mapWeightToOutputDto(ScoreWeight $weight): ScoreWeightOutputDto
    {
        $config = new AutoMapperConfig();
        $config->registerMapping(ScoreWeight::class,
            ScoreWeightOutputDto::class);
        return (new AutoMapper($config))->map($weight, ScoreWeightOutputDto::class);
    }
}
