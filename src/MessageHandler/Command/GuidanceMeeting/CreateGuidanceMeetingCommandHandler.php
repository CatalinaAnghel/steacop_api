<?php
declare(strict_types=1);

namespace App\MessageHandler\Command\GuidanceMeeting;

use App\Entity\GuidanceMeeting;
use App\Message\Command\GuidanceMeeting\CreateGuidanceMeetingCommand;
use App\Message\Event\GuidanceMeeting\GuidanceMeetingCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class CreateGuidanceMeetingCommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(CreateGuidanceMeetingCommand $command): void
    {
        /**
         * @var GuidanceMeeting $meeting
         */
        $meeting = $command->getMeeting();
        $meeting->setProject($command->getProject());
        $meeting->setUpdatedAt(new \DateTime('Now'));
        $meeting->setCreatedAt(new \DateTimeImmutable('Now'));
        $meeting->setIsCompleted(false);
        $meeting->setIsMissed(false);
        $meeting->setIsCanceled(false);
        try {
            $this->entityManager->persist($meeting);
            $this->entityManager->flush();

            date_default_timezone_set('UTC');
            $this->eventBus->dispatch(
                new GuidanceMeetingCreatedEvent(
                    $meeting,
                    [
                        $meeting->getProject()?->getStudent()?->getUser()?->getEmail(),
                        $meeting->getProject()?->getSupervisor()?->getUser()?->getEmail()
                    ]
                )
            );
            date_default_timezone_set('Europe/Bucharest');
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}