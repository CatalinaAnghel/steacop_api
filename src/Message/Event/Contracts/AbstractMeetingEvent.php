<?php
declare(strict_types=1);

namespace App\Message\Event\Contracts;

use App\Entity\Contracts\AbstractMeeting;

abstract class AbstractMeetingEvent
{
    /**
     * @param AbstractMeeting $meeting
     * @param string[] $receivers
     */
    public function __construct(private readonly AbstractMeeting $meeting, private readonly array $receivers)
    {
    }

    /**
     * @return AbstractMeeting
     */
    public function getMeeting(): AbstractMeeting
    {
        return $this->meeting;
    }

    /**
     * @return string[]
     */
    public function getReceivers(): array
    {
        return $this->receivers;
    }
}