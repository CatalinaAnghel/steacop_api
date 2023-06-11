<?php
declare(strict_types=1);

namespace App\Message\Command\GuidanceMeeting;

use App\Dto\Meeting\Input\PatchGuidanceMeetingInputDto;
use App\Entity\Contracts\AbstractMeeting;
use App\Entity\Project;
use App\Message\Command\Contracts\AbstractMeetingCommand;

class UpdateGuidanceMeetingCommand extends AbstractMeetingCommand
{
    public function __construct(
        private readonly PatchGuidanceMeetingInputDto $input,
        AbstractMeeting $meeting,
        Project $project
    ) {
        parent::__construct($meeting, $project);
    }

    /**
     * Get the value of input
     * @return PatchGuidanceMeetingInputDto
     */
    public function getInput(): PatchGuidanceMeetingInputDto
    {
        return $this->input;
    }
}