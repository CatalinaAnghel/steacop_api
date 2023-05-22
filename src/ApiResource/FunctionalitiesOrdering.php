<?php
declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
class FunctionalitiesOrdering
{
    /**
     * @var OrderCollection[] $orderingCollections
     */
    private array $orderingCollections;

    public function __construct() {
        $this->orderingCollections = [];
    }

    /**
     * @return OrderCollection[]
     */
    public function getOrderingCollections(): array
    {
        return $this->orderingCollections;
    }

    /**
     * @param OrderCollection[] $orderingCollections
     */
    public function setOrderingCollections(array $orderingCollections): void
    {
        $this->orderingCollections = $orderingCollections;
    }
}
