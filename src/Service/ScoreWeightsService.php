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
        foreach ($weights as $weight) {
            $methodName = 'get' . lcfirst($weight->getName());
            $weight->setWeight($scoreWeightDto->$methodName());
            $repository->add($weight, true);
            $config = new AutoMapperConfig();
            $config->registerMapping(ScoreWeight::class,
                ScoreWeightOutputDto::class);
            $mapper = new AutoMapper($config);
            $weightOutputDto = $mapper->map($weight, ScoreWeightOutputDto::class);
            $weightsOutputDto->addWeight($weightOutputDto);
        }
        return $weightsOutputDto;
    }
}
