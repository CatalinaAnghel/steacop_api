<?php
declare(strict_types=1);

namespace App\Message\Event\Assignment\Contracts;

use App\Entity\Assignment;

abstract class AbstractAssignmentEvent
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
