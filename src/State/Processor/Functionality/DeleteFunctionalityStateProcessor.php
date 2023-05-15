<?php
declare(strict_types=1);

namespace App\State\Processor\Functionality;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Functionality;
use App\Helper\FunctionalityStatusesHelper;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class DeleteFunctionalityStateProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly ProcessorInterface $decoratedProcessor
    ) {}

    /**
     * @inheritDoc
     * @param Functionality $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (FunctionalityStatusesHelper::Closed !== $data->getFunctionalityStatus()?->getName()) {
            // the assignment has not been turned in
            $this->decoratedProcessor->process($data, $operation, $uriVariables, $context);
        } else {
            throw new UnprocessableEntityHttpException(
                'Cannot delete a functionality that has been marked as closed'
            );
        }
    }
}
