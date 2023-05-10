<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\DataSeedingFixtureGroupTrait;
use App\Entity\SystemSetting;
use App\Helper\SystemSettingsHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

final class SystemSettingsFixture extends Fixture implements FixtureGroupInterface
{
    /**
     * @var array
     */
    protected array $variables;

    use DataSeedingFixtureGroupTrait;

    public function __construct()
    {
        $this->variables = [
            SystemSettingsHelper::AssignmentPenalization,
            SystemSettingsHelper::MilestoneMeetingsLimit
        ];
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        foreach ($this->variables as $variable) {
            $variableObject = new SystemSetting();
            $variableObject->setName($variable->getName());
            $variableObject->setValue($variable->getValue());
            $manager->persist($variableObject);
        }

        $manager->flush();
    }
}
