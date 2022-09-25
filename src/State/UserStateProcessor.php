<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserStateProcessor implements ProcessorInterface {
    private ProcessorInterface $decorated;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, ProcessorInterface $decorated) {
        $this->decorated = $decorated;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void {
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
        $this->decorated->process($data, $operation, $uriVariables, $context);
    }
}
