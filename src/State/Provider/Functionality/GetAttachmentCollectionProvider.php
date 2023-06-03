<?php
declare(strict_types=1);

namespace App\State\Provider\Functionality;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Document\Output\DocumentOutputDto;
use App\Entity\Document;
use App\Entity\FunctionalityAttachment;
use App\Paginator\StatePaginator;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Psr\Log\LoggerInterface;

class GetAttachmentCollectionProvider implements ProviderInterface
{
    public function __construct(
        private readonly ProviderInterface $decoratedProvider,
        private readonly Pagination        $pagination,
        private readonly LoggerInterface   $logger
    ) {}

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $attachments = $this->decoratedProvider->provide($operation, $uriVariables, $context);
        $attachmentsCollection = [];
        try {
            $documentConfig = new AutoMapperConfig();
            $documentConfig->registerMapping(
                FunctionalityAttachment::class,
                DocumentOutputDto::class
            )
                ->forMember('contentUrl', function (FunctionalityAttachment $document) {
                    return '/documents/functionalities/' . $document->getFunctionality()?->getId() .
                        '/' . $document->getFilePath();
                });
            $attachmentsCollection = (new AutoMapper($documentConfig))->mapMultiple(
                $attachments,
                DocumentOutputDto::class
            );
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
        $attachmentsIterator = new \ArrayIterator($attachmentsCollection);

        [$page, $offset, $limit] = $this->pagination->getPagination(
            $operation, $context);
        $max = count($attachmentsCollection);
        return new StatePaginator($attachmentsIterator, $page, $limit, $max);
    }
}
