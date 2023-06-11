<?php
declare(strict_types=1);

namespace App\MessageHandler\Command\Assignment;

use App\Entity\Student;
use App\Message\Command\Assignment\UpdateAssignmentCommand;
use App\Message\Event\Assignment\AssignmentGradedEvent;
use App\Message\Event\Assignment\AssignmentUpdatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class UpdateAssignmentCommandHandler
{
    public function __construct(
        private readonly MessageBusInterface $eventBus,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(UpdateAssignmentCommand $command): void
    {
        $assignment = $command->getAssignment();
        $input = $command->getInput();
        $gradeEvent = null === $assignment->getGrade() && null !== $input->getGrade() ||
            (null !== $input->getGrade() && $input->getGrade() !== $assignment->getGrade());
        $editEvent = $assignment->getTitle() !== $input->getTitle() ||
            $assignment->getDescription() !== $input->getDescription() ||
            $assignment->getDueDate() !== $input->getDueDate();
        $assignment->setGrade($input->getGrade());
        $assignment->setTitle($input->getTitle());
        $assignment->setDescription($input->getDescription());
        if (null === $input->getGrade()) {
            $turnInDate = $input->isTurnedIn() ? new \DateTime('Now') : null;
            $assignment->setTurnedInDate($turnInDate);
        }
        $assignment->setDueDate($input->getDueDate());
        $assignment->setUpdatedAt(new \DateTime('Now'));
        $this->entityManager->flush();

        if ($editEvent || $gradeEvent) {
            date_default_timezone_set('UTC');
            $studentRepo = $this->entityManager->getRepository(Student::class);
            $student = $studentRepo->findOneBy(['project' => $command->getProject()->getId()]);
            if ($gradeEvent) {
                $event = new AssignmentGradedEvent(
                    $assignment,
                    $student?->getUser()?->getEmail()
                );
            } else {
                $event = new AssignmentUpdatedEvent(
                    $assignment,
                    $student?->getUser()?->getEmail()
                );
            }

            $this->eventBus->dispatch($event);

            date_default_timezone_set('Europe/Bucharest');
        }
    }
}