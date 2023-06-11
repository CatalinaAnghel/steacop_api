<?php
declare(strict_types=1);

namespace App\Message\Command\Assignment;

use App\Entity\Assignment;
use App\Entity\Project;
use App\Message\Command\Assignment\Contracts\AbstractAssignmentCommand;

class CreateAssignmentCommand extends AbstractAssignmentCommand
{
    public function __construct(
        Assignment $assignment,
        Project    $project
    ) {
        parent::__construct($assignment, $project);
    }

}
