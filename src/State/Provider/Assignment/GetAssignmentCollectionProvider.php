<?php
declare(strict_types=1);

namespace App\State\Provider\Assignment;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Assignment\Output\AssignmentOutputDto;
use App\Dto\Document\Output\DocumentOutputDto;
use App\Entity\Assignment;
use App\Entity\Document;
use App\Paginator\StatePaginator;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;

class GetAssignmentCollectionProvider implements ProviderInterface {
    public function __construct(private readonly ProviderInterface $decoratedProvider,
                                private readonly Pagination        $pagination) {
        date_default_timezone_set('Europe/Bucharest');
    }


    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null {
        $assignments = $this->decoratedProvider->provide($operation, $uriVariables, $context);
        $config = new AutoMapperConfig();
        $config
            ->registerMapping(Assignment::class, AssignmentOutputDto::class)
            ->forMember('documents', function(Assignment $source): array{
                $documentConfig = new AutoMapperConfig();
                $documentConfig->registerMapping(
                    Document::class,
                    DocumentOutputDto::class
                )
                ->forMember('contentUrl', function(Document $document){
                    return '/documents/assignments/' . $document->getAssignment()?->getId() .
                        '/' . $document->getFilePath();
                });
                return (new AutoMapper($documentConfig))->mapMultiple(
                    $source->getDocuments(),
                    DocumentOutputDto::class
                );
            })
        ;
        $mapper = new AutoMapper($config);
        $assignmentsCollection = $mapper->mapMultiple($assignments, AssignmentOutputDto::class);
        $assignmentsIterator = new \ArrayIterator($assignmentsCollection);

        [$page, $offset, $limit] = $this->pagination->getPagination(
            $operation, $context);
        $max = count($assignmentsCollection);
        return new StatePaginator($assignmentsIterator, $page, $limit, $max);
    }
}
