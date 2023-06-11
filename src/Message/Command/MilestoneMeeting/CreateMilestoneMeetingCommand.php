<?php
declare(strict_types=1);

namespace App\Message\Command\MilestoneMeeting;

use App\Entity\Contracts\AbstractMeeting;
use App\Entity\Project;
use App\Message\Command\Contracts\AbstractMeetingCommand;

class CreateMilestoneMeetingCommand extends AbstractMeetingCommand
{
    public function __construct(
        AbstractMeeting $meeting,
        Project    $project
    ) {
        parent::__construct($meeting, $project);
    }
}
