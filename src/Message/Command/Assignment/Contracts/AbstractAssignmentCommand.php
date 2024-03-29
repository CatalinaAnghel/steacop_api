<?php
declare(strict_types=1);

namespace App\Message\Command\Assignment\Contracts;

use App\Entity\Assignment;
use App\Entity\Project;

abstract class AbstractAssignmentCommand
{
    public function __construct(
        private readonly Assignment $assignment,
        private readonly Project    $project
    ) {}

    /**
     * @return Assignment
     */
    public function getAssignment(): Assignment
    {
        return $this->assignment;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }
}
