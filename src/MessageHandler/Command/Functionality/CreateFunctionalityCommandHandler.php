<?php
declare(strict_types=1);

namespace App\MessageHandler\Command\Functionality;
use App\Message\Command\Functionality\CreateFunctionalityCommand;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateFunctionalityCommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger
    ) {}

    public function __invoke(CreateFunctionalityCommand $command): void
    {
        $functionality = $command->getFunctionality();
        $functionality->setCreatedAt(new \DateTimeImmutable('Now'));
        $functionality->setUpdatedAt(new \DateTime('Now'));
        $functionality->setProject($command->getProject());

        try{
            $this->entityManager->persist($functionality);
            $this->entityManager->flush();
        }catch (\Exception $exception){
            $this->logger->error($exception->getMessage());
        }
    }
}
