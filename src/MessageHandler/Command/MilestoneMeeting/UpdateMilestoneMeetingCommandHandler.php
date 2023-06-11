<?php
declare(strict_types=1);

namespace App\MessageHandler\Command\MilestoneMeeting;

use App\Entity\MilestoneMeeting;
use App\Message\Command\MilestoneMeeting\UpdateMilestoneMeetingCommand;
use App\Message\Event\MilestoneMeeting\MilestoneMeetingCanceledEvent;
use App\Message\Event\MilestoneMeeting\MilestoneMeetingGradedEvent;
use App\Message\Event\MilestoneMeeting\MilestoneMeetingMissedEvent;
use App\Message\Event\MilestoneMeeting\MilestoneMeetingUpdatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class UpdateMilestoneMeetingCommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(UpdateMilestoneMeetingCommand $command): void
    {
        $event = null;
        try {
            /**
             * @var MilestoneMeeting $milestoneMeeting
             */
            $milestoneMeeting = $command->getMeeting();
            $milestoneMeeting->setDescription($command->getInput()->getDescription());
            $milestoneMeeting->setScheduledAt($command->getInput()->getScheduledAt());
            $milestoneMeeting->setLink($command->getInput()->getLink());
            $milestoneMeeting->setDuration($command->getInput()->getDuration());
            $receivers = [
                $milestoneMeeting->getProject()?->getStudent()?->getUser()?->getEmail(),
                $milestoneMeeting->getProject()?->getSupervisor()?->getUser()?->getEmail()
            ];
            if (null !== $command->getInput()->getGrade()) {
                if (null === $milestoneMeeting->getGrade()) {
                    // grading event
                    $event = new MilestoneMeetingGradedEvent(
                        $milestoneMeeting,
                        $receivers
                    );
                }
                $milestoneMeeting->setGrade($command->getInput()->getGrade());
            }
            $milestoneMeeting->setUpdatedAt(new \DateTime('Now'));
            $milestoneMeeting->setIsCompleted($command->getInput()->getIsCompleted());
            if ($command->getInput()->getIsCanceled()) {
                $milestoneMeeting->setIsCanceled(true);
                $milestoneMeeting->setCanceledAt(new \DateTimeImmutable('Now'));
                $event = new MilestoneMeetingCanceledEvent(
                    $milestoneMeeting,
                    $receivers
                );
            }

            if ($command->getInput()->getIsMissed()) {
                $milestoneMeeting->setIsMissed(true);
                $event = new MilestoneMeetingMissedEvent(
                    $milestoneMeeting,
                    $receivers
                );
            }
            $this->entityManager->persist($milestoneMeeting);
            $this->entityManager->flush();

            if (null === $event) {
                $event = new MilestoneMeetingUpdatedEvent(
                    $milestoneMeeting,
                    $receivers
                );
            }

            date_default_timezone_set('UTC');
            $this->eventBus->dispatch($event);
            date_default_timezone_set('Europe/Bucharest');
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}