<?php
declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\Project;

trait ProjectTrait
{
    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): void
    {
        $this->project = $project;
    }
}
