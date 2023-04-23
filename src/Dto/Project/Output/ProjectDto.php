<?php
declare(strict_types=1);

namespace App\Dto\Project\Output;

use App\Dto\Traits\IdentityTrait;

class ProjectDto
{
    use IdentityTrait;

    /**
     * @var ?string $title
     */
    private ?string $title;

    /**
     * @var ?string $description
     */
    private ?string $description;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(string|null $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): string|null
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(string|null $description): void
    {
        $this->description = $description;
    }
}
