<?php
declare(strict_types=1);

namespace App\Message\Event;

use App\Entity\Assignment;
use App\Entity\User;

class AssignmentCreatedEvent
{
    public function __construct(private readonly Assignment $assignment, private readonly string $receiver) {}

    /**
     * @return Assignment
     */
    public function getAssignment(): Assignment
    {
        return $this->assignment;
    }

    /**
     * @return string
     */
    public function getReceiver(): string
    {
        return $this->receiver;
    }
}
