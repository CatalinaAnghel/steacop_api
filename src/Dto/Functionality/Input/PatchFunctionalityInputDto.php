<?php
declare(strict_types=1);

namespace App\Dto\Functionality\Input;

use App\Dto\Functionality\Input\Contracts\AbstractFunctionalityInputDto;

class PatchFunctionalityInputDto extends AbstractFunctionalityInputDto
{
    /**
     * @var int $status
     */
    private int $status;

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
}
