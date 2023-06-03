<?php
declare(strict_types=1);

namespace App\State\Provider\Functionality;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\FunctionalityAttachment;
use Psr\Log\LoggerInterface;

class GetAttachmentItemProvider implements ProviderInterface
{
    public function __construct(private readonly ProviderInterface $decoratedProvider)
    {
        date_default_timezone_set('Europe/Bucharest');
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /**
         * @var FunctionalityAttachment $attachment
         */
        $attachment = $this->decoratedProvider->provide($operation, $uriVariables, $context);
        $attachment?->setFunctionalityId((string)$attachment->getFunctionality()?->getId());

        return $attachment;
    }
}
