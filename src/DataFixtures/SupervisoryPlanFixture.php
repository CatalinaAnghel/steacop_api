<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\SupervisoryPlan;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class SupervisoryPlanFixture extends Fixture implements FixtureGroupInterface {

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void {
        $laissezFairePlan = new SupervisoryPlan();
        $laissezFairePlan->setName('Laissez-faire');
        $laissezFairePlan->setHasLowStructure(true);
        $laissezFairePlan->setHasLowSupport(true);
        $manager->persist($laissezFairePlan);

        $pastoralPlan = new SupervisoryPlan();
        $pastoralPlan->setName('Pastoral');
        $pastoralPlan->setHasLowSupport(false);
        $pastoralPlan->setHasLowStructure(true);
        $manager->persist($pastoralPlan);

        $directorialPlan = new SupervisoryPlan();
        $directorialPlan->setName('Directorial');
        $directorialPlan->setHasLowSupport(true);
        $directorialPlan->setHasLowStructure(false);
        $manager->persist($directorialPlan);

        $contractualPlan = new SupervisoryPlan();
        $contractualPlan->setName('Contractual');
        $contractualPlan->setHasLowSupport(false);
        $contractualPlan->setHasLowStructure(false);
        $manager->persist($contractualPlan);
        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public static function getGroups(): array {
        return [
          'data_seeding'
        ];
    }
}
