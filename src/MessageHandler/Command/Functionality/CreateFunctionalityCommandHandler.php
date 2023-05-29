<?php
declare(strict_types=1);

namespace App\MessageHandler\Command\Functionality;

use App\Entity\Functionality;
use App\Entity\ProjectFunctionalitiesHistory;
use App\Message\Command\Functionality\CreateFunctionalityCommand;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateFunctionalityCommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $logger
    ) {}

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
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}
