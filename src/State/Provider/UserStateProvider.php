<?php

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\UserRepository;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\UnregisteredMappingException;

class UserStateProvider implements ProviderInterface {
    public function __construct(private readonly UserRepository $repository) {
    }

    /**
     * @inheritDoc
     * @throws UnregisteredMappingException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|null {
        $user = $this->repository->findOneBy(['code' => $uriVariables['code']]);

        if(null !== $user){
            $config = new AutoMapperConfig();
            $config->registerMapping(\App\Entity\User::class,
                \App\ApiResource\User::class);
            $mapper = new AutoMapper($config);
            $userResource = $mapper->map($user, \App\ApiResource\User::class);
        }

        return $userResource?? null;
    }
}
