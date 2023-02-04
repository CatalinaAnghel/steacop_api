<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Department;
use App\Entity\Specialization;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SpecializationFixture extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {

    /**
     * @inheritDoc
     */
    public function getDependencies(): array {
        return [
            DepartmentFixture::class
        ];
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void {
        $crSpecialization = new Specialization();
        $crSpecialization->setName('Calculatoare cu predare in limba romana');
        $crSpecialization->setDepartment($this->getReference(DepartmentFixture::DEPARTMENT_REFERENCE));
        $manager->persist($crSpecialization);

        $ceSpecialization = new Specialization();
        $ceSpecialization->setName('Calculatoare cu predare in limba engleza');
        $ceSpecialization->setDepartment($this->getReference(DepartmentFixture::DEPARTMENT_REFERENCE));
        $manager->persist($ceSpecialization);

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
