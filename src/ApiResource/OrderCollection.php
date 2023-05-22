<?php
declare(strict_types=1);

namespace App\ApiResource;

class OrderCollection
{
    /**
     * @var int $statusId
     */
    private int $statusId;

    /**
     * @var int[] $functionalities
     */
    private array $functionalities;

    /**
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->statusId;
    }

    /**
     * @param int $statusId
     */
    public function setStatusId(int $statusId): void
    {
        $this->statusId = $statusId;
    }

    /**
     * @return array
     */
    public function getFunctionalities(): array
    {
        return $this->functionalities;
    }

    /**
     * @param array $functionalities
     */
    public function setFunctionalities(array $functionalities): void
    {
        $this->functionalities = $functionalities;
    }
}
