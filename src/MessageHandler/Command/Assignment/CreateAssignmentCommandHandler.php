<?php
declare(strict_types=1);

namespace App\MessageHandler\Command\Assignment;

use App\Entity\Student;
use App\Message\Command\Assignment\CreateAssignmentCommand;
use App\Message\Event\Assignment\AssignmentCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class CreateAssignmentCommandHandler
{
    public function __construct(
        private readonly MessageBusInterface    $eventBus,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function __invoke(CreateAssignmentCommand $command): void
    {
        $assignment = $command->getAssignment();
        $assignment->setCreatedAt(new \DateTimeImmutable('Now'));
        $assignment->setUpdatedAt(new \DateTime('Now'));
        $assignment->setProject($command->getProject());
        $this->entityManager->persist($assignment);
        $this->entityManager->flush();

        date_default_timezone_set('UTC');
        $studentRepo = $this->entityManager->getRepository(Student::class);
        $student = $studentRepo->findOneBy(['project' => $command->getProject()->getId()]);
        $this->eventBus->dispatch(new AssignmentCreatedEvent(
            $assignment,
            $student?->getUser()?->getEmail()
        ));
        date_default_timezone_set('Europe/Bucharest');
    }
}
