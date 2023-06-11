<?php
declare(strict_types=1);

namespace App\MessageHandler\Command\Functionality;

use App\Entity\Functionality;
use App\Entity\ProjectFunctionalitiesHistory;
use App\Message\Command\Functionality\CreateFunctionalityCommand;
use App\Message\Event\Functionality\FunctionalityCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class CreateFunctionalityCommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(CreateFunctionalityCommand $command): void
    {
        $date = new \DateTime('Now');
        $functionality = $command->getFunctionality();
        $functionality->setCreatedAt(\DateTimeImmutable::createFromMutable($date));
        $functionality->setUpdatedAt($date);
        $functionality->setProject($command->getProject());

        $functionalityRepo = $this->entityManager->getRepository(Functionality::class);
        $status = $functionality->getFunctionalityStatus();
        $numberOfFunctionalities = $functionalityRepo->count([
            'functionalityStatus' => $status?->getId(),
            'project' => $command->getProject()
        ]) + 1;

        $history = new ProjectFunctionalitiesHistory();
        $history->setProject($command->getProject())
            ->setStatus($status)
            ->setCreatedAt(\DateTimeImmutable::createFromMutable($date))
            ->setUpdatedAt($date)
            ->setNumberOfFunctionalities($numberOfFunctionalities);
        $this->entityManager->persist($history);
        try {
            $this->entityManager->persist($functionality);
            $this->entityManager->flush();

            date_default_timezone_set('UTC');
            $this->eventBus->dispatch(
                new FunctionalityCreatedEvent(
                    $functionality,
                    [
                        $functionality->getProject()?->getStudent()?->getUser()?->getEmail(),
                        $functionality->getProject()?->getSupervisor()?->getUser()?->getEmail()
                    ]
                )
            );
            date_default_timezone_set('Europe/Bucharest');
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}
