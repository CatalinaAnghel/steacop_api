<?php
declare(strict_types=1);

namespace App\MessageHandler\Command\GuidanceMeeting;

use App\Entity\GuidanceMeeting;
use App\Message\Command\GuidanceMeeting\UpdateGuidanceMeetingCommand;
use App\Message\Event\GuidanceMeeting\GuidanceMeetingCanceledEvent;
use App\Message\Event\GuidanceMeeting\GuidanceMeetingMissedEvent;
use App\Message\Event\GuidanceMeeting\GuidanceMeetingUpdatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class UpdateGuidanceMeetingCommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(UpdateGuidanceMeetingCommand $command): void
    {
        $event = null;
        try {
            /**
             * @var GuidanceMeeting $guidanceMeeting
             */
            $guidanceMeeting = $command->getMeeting();
            $guidanceMeeting->setDescription($command->getInput()->getDescription());
            $guidanceMeeting->setDuration($command->getInput()->getDuration());
            $guidanceMeeting->setScheduledAt($command->getInput()->getScheduledAt());
            $guidanceMeeting->setLink($command->getInput()->getLink());
            $guidanceMeeting->setUpdatedAt(new \DateTime('Now'));
            $guidanceMeeting->setIsCompleted($command->getInput()->getIsCompleted());
            if ($command->getInput()->getIsCanceled()) {
                $guidanceMeeting->setIsCanceled(true);
                $guidanceMeeting->setCanceledAt(new \DateTimeImmutable('Now'));
                $event = new GuidanceMeetingCanceledEvent(
                    $guidanceMeeting,
                    [
                        $guidanceMeeting->getProject()?->getStudent()?->getUser()?->getEmail(),
                        $guidanceMeeting->getProject()?->getSupervisor()?->getUser()?->getEmail()
                    ]
                    );
            }

            if ($command->getInput()->getIsMissed()) {
                $eventTriggered = true;
                $guidanceMeeting->setIsMissed(true);
                $event = new GuidanceMeetingMissedEvent(
                    $guidanceMeeting,
                    [
                        $guidanceMeeting->getProject()?->getStudent()?->getUser()?->getEmail(),
                        $guidanceMeeting->getProject()?->getSupervisor()?->getUser()?->getEmail()
                    ]
                    );
            }
            $this->entityManager->persist($guidanceMeeting);
            $this->entityManager->flush();

            if(null === $event){
                $event = new GuidanceMeetingUpdatedEvent(
                    $guidanceMeeting,
                    [
                        $guidanceMeeting->getProject()?->getStudent()?->getUser()?->getEmail(),
                        $guidanceMeeting->getProject()?->getSupervisor()?->getUser()?->getEmail()
                    ]
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