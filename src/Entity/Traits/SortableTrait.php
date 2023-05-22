<?php
declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait SortableTrait
{
    #[ORM\Column]
    private ?int $orderNumber = null;

    public function getOrderNumber(): ?int
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(int $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }
}
