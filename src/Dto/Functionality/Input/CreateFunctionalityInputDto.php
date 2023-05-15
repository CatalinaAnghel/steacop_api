<?php
declare(strict_types=1);

namespace App\Dto\Functionality\Input;

use App\Dto\Functionality\Input\Contracts\AbstractFunctionalityInputDto;

class CreateFunctionalityInputDto extends AbstractFunctionalityInputDto
{
    /**
     * @var int $projectId
     */
    private int $projectId;

    /**
     * @return int
     */
    public function getProjectId(): int
    {
        return $this->projectId;
    }

    /**
     * @param int $projectId
     */
    public function setProjectId(int $projectId): void
    {
        $this->projectId = $projectId;
    }
}
