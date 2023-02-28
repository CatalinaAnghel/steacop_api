<?php
declare(strict_types=1);

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;

final class SupervisorImportFileStateProcessor implements ProcessorInterface {
    public function __construct(private readonly ProcessorInterface $decorated) {
    }

    /**
     * @inheritDoc
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void {
        $this->decorated->process($data, $operation, $uriVariables, $context);
    }
}
