<?php
declare(strict_types=1);

namespace App\Message\Command\Assignment;

use App\Dto\Assignment\Input\PatchAssignmentInputDto;
use App\Entity\Assignment;
use App\Entity\Project;
use App\Message\Command\Assignment\Contracts\AbstractAssignmentCommand;

class UpdateAssignmentCommand extends AbstractAssignmentCommand
{
    public function __construct(
        private readonly PatchAssignmentInputDto $input,
        Assignment $assignment,
        Project $project
    ) {
        parent::__construct($assignment, $project);
    }

    /**
     * Get the value of input
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Set the value of input
     * @param PatchAssignmentInputDto $input
     * @return  self
     */
    public function setInput(PatchAssignmentInputDto $input)
    {
        $this->input = $input;

        return $this;
    }
}