<?php
declare(strict_types=1);

namespace App\State\Provider\GuidanceMeeting;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Meeting\Output\GuidanceMeetingOutputDto;
use App\Paginator\StatePaginator;
use App\State\Provider\Contracts\AbstractGuidanceMeetingProvider;

class GetGuidanceMeetingsCollectionProvider extends AbstractGuidanceMeetingProvider
{
    public function __construct(private readonly ProviderInterface $decoratedProvider,
                                private readonly Pagination        $pagination)
    {
        date_default_timezone_set('Europe/Bucharest');
    }


    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $meetings = $this->decoratedProvider->provide($operation, $uriVariables, $context);
        $mapper = $this->getMapper();
        $meetingsCollection = $mapper->mapMultiple($meetings, GuidanceMeetingOutputDto::class);
        $meetingIterator = new \ArrayIterator($meetingsCollection);

        [$page, $offset, $limit] = $this->pagination->getPagination(
            $operation, $context);
        $max = count($meetingsCollection);
        return new StatePaginator($meetingIterator, $page, $limit, $max);
    }
}
