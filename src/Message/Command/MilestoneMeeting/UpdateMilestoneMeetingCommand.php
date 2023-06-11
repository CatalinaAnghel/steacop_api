<?php
declare(strict_types=1);

namespace App\Message\Command\MilestoneMeeting;

use App\Dto\Meeting\Input\PatchMilestoneMeetingInputDto;
use App\Entity\Contracts\AbstractMeeting;
use App\Entity\Project;
use App\Message\Command\Contracts\AbstractMeetingCommand;

class UpdateMilestoneMeetingCommand extends AbstractMeetingCommand
{
    public function __construct(
        private readonly PatchMilestoneMeetingInputDto $input,
        AbstractMeeting $meeting,
        Project $project
    ) {
        parent::__construct($meeting, $project);
    }

    /**
     * Get the value of input
     * @return PatchMilestoneMeetingInputDto
     */
    public function getInput(): PatchMilestoneMeetingInputDto
    {
        return $this->input;
    }
}