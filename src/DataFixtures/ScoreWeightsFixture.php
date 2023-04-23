<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\DataSeedingFixtureGroupTrait;
use App\Entity\ScoreWeight;
use App\Helper\WeightsHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

final class ScoreWeightsFixture extends Fixture implements FixtureGroupInterface
{
    /**
     * @var array
     */
    protected array $variables;

    use DataSeedingFixtureGroupTrait;

    public function __construct()
    {
        $this->variables = [
            WeightsHelper::RatingWeight,
            WeightsHelper::SupportWeight,
            WeightsHelper::StructureWeight,
            WeightsHelper::StructurePenalty
        ];
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        foreach ($this->variables as $variable) {
            $variableObject = new ScoreWeight();
            $variableObject->setName($variable->getWeightName());
            $variableObject->setDescription($variable->getWeightName());
            $variableObject->setWeight($variable->getWeightValue());
            $manager->persist($variableObject);
        }

        $manager->flush();
    }
}
