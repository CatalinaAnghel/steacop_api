<?php

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\User;
use App\Repository\UserRepository;

class UserStateProvider implements ProviderInterface {
    public function __construct(private readonly UserRepository $repository) {
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|null {
        $user = $this->repository->findOneBy(['code' => $uriVariables['code']]);

        $userResource = new User();
        if(null !== $user){
            $userResource->setCode($user->getCode());
            $userType = explode('\\', get_class($user));
            $userResource->setDiscriminator(end($userType));
        }

        return $userResource;
    }
}
