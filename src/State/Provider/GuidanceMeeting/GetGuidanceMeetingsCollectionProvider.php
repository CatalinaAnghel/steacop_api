<?php
declare(strict_types=1);

namespace App\State\Provider\GuidanceMeeting;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Meeting\Output\GuidanceMeetingOutputDto;
use App\Entity\GuidanceMeeting;
use App\Paginator\StatePaginator;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;

class GetGuidanceMeetingsCollectionProvider implements ProviderInterface {
    public function __construct(private readonly ProviderInterface $decoratedProvider,
                                private readonly Pagination        $pagination) {
    }


    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null {
        $meetings = $this->decoratedProvider->provide($operation, $uriVariables, $context);
        $config = new AutoMapperConfig();
        $config
            ->registerMapping(GuidanceMeeting::class, GuidanceMeetingOutputDto::class);
        $mapper = new AutoMapper($config);
        $meetingsCollection = $mapper->mapMultiple($meetings, GuidanceMeetingOutputDto::class);
        $meetingIterator = new \ArrayIterator($meetingsCollection);

        [$page, $offset, $limit] = $this->pagination->getPagination(
            $operation, $context);
        $max = count($meetingsCollection);
        return new StatePaginator($meetingIterator, $page, $limit, $max);
    }
}
