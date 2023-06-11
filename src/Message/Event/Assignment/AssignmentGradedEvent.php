<?php
declare(strict_types=1);

namespace App\Message\Event\Assignment;

use App\Entity\Assignment;
use App\Message\Event\Assignment\Contracts\AbstractAssignmentEvent;

class AssignmentGradedEvent extends AbstractAssignmentEvent
{
    public function __construct(Assignment $assignment, string $receiver) {
        parent::__construct($assignment, $receiver);
    }
}
