<?php
declare(strict_types=1);

namespace App\State\Processor\Assignment;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Assignment;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class DeleteAssignmentStateProcessor implements ProcessorInterface {
    public function __construct(
        private readonly ProcessorInterface $decoratedProcessor
    ) {
    }

    /**
     * @inheritDoc
     * @param Assignment $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void {
        if (null === $data->getTurnedInDate()) {
            // the assignment has not been turned in
            $this->decoratedProcessor->process($data, $operation, $uriVariables, $context);
        } else {
            throw new UnprocessableEntityHttpException(
                'Once the assignment has been marked as turned in, it cannot be removed'
            );
        }
    }
}
