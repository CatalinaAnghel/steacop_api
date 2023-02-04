<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminAccountFixture extends Fixture implements FixtureGroupInterface {
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher,
                                private readonly string $adminEmail,
                                private readonly string $adminPassword,
                                private readonly string $adminCode) {
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void {
        $admin = new User();
        $admin->setCode($this->adminCode);
        $admin->setEmail($this->adminEmail);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, $this->adminPassword));
        $manager->persist($admin);
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
