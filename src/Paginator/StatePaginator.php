<?php
declare(strict_types=1);

namespace App\Paginator;

use ApiPlatform\State\Pagination\PaginatorInterface;
use Traversable;

class StatePaginator implements PaginatorInterface, \IteratorAggregate
{

    public function __construct(private readonly \ArrayIterator $elements,
                                private readonly int            $currentPage,
                                private readonly int            $maxResults,
                                private readonly int            $totalNumberOfItems
    ) {}

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return $this->elements;
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function count(): int
    {
        return iterator_count($this->getIterator());
    }

    /**
     * @inheritDoc
     */
    public function getLastPage(): float
    {
        return ceil($this->getTotalItems() / $this->getItemsPerPage()) ?? 1.;
    }

    /**
     * @inheritDoc
     */
    public function getTotalItems(): float
    {
        return (float)$this->totalNumberOfItems;
    }

    /**
     * @inheritDoc
     */
    public function getCurrentPage(): float
    {
        return $this->currentPage ?? 1;
    }

    /**
     * @inheritDoc
     */
    public function getItemsPerPage(): float
    {
        return $this->maxResults;
    }
}
