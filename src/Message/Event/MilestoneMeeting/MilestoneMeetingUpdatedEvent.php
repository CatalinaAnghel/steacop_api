<?php
declare(strict_types=1);

namespace App\Message\Event\MilestoneMeeting;

use App\Entity\Contracts\AbstractMeeting;
use App\Message\Event\Contracts\AbstractMeetingEvent;

class MilestoneMeetingUpdatedEvent extends AbstractMeetingEvent
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