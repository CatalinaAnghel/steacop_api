<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\DataSeedingFixtureGroupTrait;
use App\Entity\SupervisoryPlan;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

final class SupervisoryPlanFixture extends Fixture implements FixtureGroupInterface {
    use DataSeedingFixtureGroupTrait;

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void {
        $laissezFairePlan = new SupervisoryPlan();
        $laissezFairePlan->setName('Laissez-faire');
        $laissezFairePlan->setHasLowStructure(true);
        $laissezFairePlan->setHasLowSupport(true);
        $laissezFairePlan->setNumberOfAssignments(0);
        $laissezFairePlan->setNumberOfGuidanceMeetings(0);
        $manager->persist($laissezFairePlan);

        $pastoralPlan = new SupervisoryPlan();
        $pastoralPlan->setName('Pastoral');
        $pastoralPlan->setHasLowSupport(false);
        $pastoralPlan->setHasLowStructure(true);
        $pastoralPlan->setNumberOfAssignments(0);
        $pastoralPlan->setNumberOfGuidanceMeetings(0);
        $manager->persist($pastoralPlan);

        $directorialPlan = new SupervisoryPlan();
        $directorialPlan->setName('Directorial');
        $directorialPlan->setHasLowSupport(true);
        $directorialPlan->setHasLowStructure(false);
        $directorialPlan->setNumberOfAssignments(0);
        $directorialPlan->setNumberOfGuidanceMeetings(0);
        $manager->persist($directorialPlan);

        $contractualPlan = new SupervisoryPlan();
        $contractualPlan->setName('Contractual');
        $contractualPlan->setHasLowSupport(false);
        $contractualPlan->setHasLowStructure(false);
        $contractualPlan->setNumberOfAssignments(0);
        $contractualPlan->setNumberOfGuidanceMeetings(0);
        $manager->persist($contractualPlan);
        $manager->flush();
    }
}
