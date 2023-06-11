<?php
declare(strict_types=1);

namespace App\MessageHandler\Command\MilestoneMeeting;

use App\Entity\MilestoneMeeting;
use App\Message\Command\MilestoneMeeting\CreateMilestoneMeetingCommand;
use App\Message\Event\MilestoneMeeting\MilestoneMeetingCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class CreateMilestoneMeetingCommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(CreateMilestoneMeetingCommand $command): void
    {
        /**
         * @var MilestoneMeeting $meeting
         */
        $meeting = $command->getMeeting();
        $meeting->setProject($command->getProject());
        $meeting->setUpdatedAt(new \DateTime('Now'));
        $meeting->setCreatedAt(new \DateTimeImmutable('Now'));
        $meeting->setIsCompleted(false);
        $meeting->setIsCanceled(false);
        $meeting->setIsMissed(false);

        try {
            $this->entityManager->persist($meeting);
            $this->entityManager->flush();

            date_default_timezone_set('UTC');
            $this->eventBus->dispatch(
                new MilestoneMeetingCreatedEvent(
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