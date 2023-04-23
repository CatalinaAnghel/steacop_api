<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserStateProcessor implements ProcessorInterface
{
    public const ROLES = [
        'student' => 'ROLE_STUDENT',
        'supervisor' => 'ROLE_TEACHER'
    ];
    private ProcessorInterface $decorated;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, ProcessorInterface $decorated)
    {
        $this->decorated = $decorated;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        // Handle the state
        $data->setRoles($data->getRoles());

        if ($data->getPlainPassword()) {
            // hash the plain password
            $data->setPassword(
                $this->userPasswordHasher->hashPassword($data, $data->getPlainPassword())
            );

            // erase the credentials that are temporarily stored
            $data->eraseCredentials();
        }

        // create the new user
        $config = new AutoMapperConfig();
        $config->registerMapping(\App\ApiResource\User::class,
            'App\\Entity\\' . $data->getDiscriminator());
        $mapper = new AutoMapper($config);
        $newUser = $mapper->map($data, 'App\\Entity\\' . $data->getDiscriminator());

        $this->decorated->process($newUser, $operation, $uriVariables, $context);
    }
}
