<?php
declare(strict_types=1);

namespace App\Message\Event\GuidanceMeeting;

use App\Entity\Contracts\AbstractMeeting;
use App\Message\Event\Contracts\AbstractMeetingEvent;

class GuidanceMeetingUpdatedEvent extends AbstractMeetingEvent
{
    /**
     * @param AbstractMeeting $meeting
     * @param string[] $receivers
     */
    public function __construct(AbstractMeeting $meeting, array $receivers)
    {
        parent::__construct($meeting, $receivers);
    }
}