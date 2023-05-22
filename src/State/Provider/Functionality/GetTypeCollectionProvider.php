<?php
declare(strict_types=1);

namespace App\State\Provider\Functionality;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Functionality\Output\TypeOutputDto;
use App\Paginator\StatePaginator;
use App\State\Provider\Contracts\AbstractTypeProvider;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class GetTypeCollectionProvider extends AbstractTypeProvider
{
    public function __construct(
        private readonly ProviderInterface      $decoratedProvider,
        private readonly Pagination             $pagination,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $logger
    )
    {
        parent::__construct($this->entityManager);
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $types = $this->decoratedProvider->provide($operation, $uriVariables, $context);
        $mapper = $this->getMapper();
        try {
            $typesCollection = $mapper->mapMultiple($types, TypeOutputDto::class);
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());die;
            $this->logger->error($exception->getMessage());
            $typesCollection = [];
        }

        $typesIterator = new \ArrayIterator($typesCollection);

        [$page, $offset, $limit] = $this->pagination->getPagination(
            $operation, $context);
        $max = count($typesCollection);
        return new StatePaginator($typesIterator, $page, $limit, $max);
    }
}
