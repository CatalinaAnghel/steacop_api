<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\DataSeedingFixtureGroupTrait;
use App\Entity\Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class DepartmentFixture extends Fixture implements FixtureGroupInterface
{
    public const DEPARTMENT_REFERENCE = 'dcti_department';

    use DataSeedingFixtureGroupTrait;

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $department = new Department();
        $department->setName('DCTI');
        $manager->persist($department);
        $manager->flush();

        $this->addReference(self::DEPARTMENT_REFERENCE, $department);
    }
}
