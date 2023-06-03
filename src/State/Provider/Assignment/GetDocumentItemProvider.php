<?php
declare(strict_types=1);

namespace App\State\Provider\Assignment;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Document;

class GetDocumentItemProvider implements ProviderInterface
{
    public function __construct(private readonly ProviderInterface $decoratedProvider) {}

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /**
         * @var Document $document
         */
        $document = $this->decoratedProvider->provide($operation, $uriVariables, $context);
        $document?->setAssignmentId((string)$document->getAssignment()?->getId());

        return $document;
    }
}
