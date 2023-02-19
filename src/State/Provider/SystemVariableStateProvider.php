<?php
declare(strict_types=1);

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

class SystemVariableStateProvider implements ProviderInterface {
    public function __construct(private readonly ProviderInterface $decorated) {
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null {

        dump($operation);
        dump($uriVariables);
        dd($context);

        return $this->decorated->provide($operation, $uriVariables, $context);
    }
}