<?php
declare(strict_types=1);

namespace App\Message\Command\Contracts;

use App\Entity\Contracts\AbstractMeeting;
use App\Entity\Project;

abstract class AbstractMeetingCommand
{
    public function __construct(
        private readonly AbstractMeeting $meeting,
        private readonly Project    $project
    ) {}

    /**
     * @return AbstractMeeting
     */
    public function getMeeting(): AbstractMeeting
    {
        return $this->meeting;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }
}
