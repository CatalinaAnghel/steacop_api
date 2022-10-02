<?php

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\UserRepository;

class UserStateProvider implements ProviderInterface {
    public function __construct(private UserRepository $repository) {
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|iterable|null {
        return $this->repository->findOneBy(['code' => $uriVariables['code']]);
    }
}