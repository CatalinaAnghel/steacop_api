<?php
declare(strict_types=1);

namespace App\State\Processor\Functionality;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\FunctionalitiesOrdering;
use App\Entity\Functionality;
use App\Entity\FunctionalityStatus;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class OrderFunctionalitiesProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $logger
    ) {}

    /**
     * @inheritDoc
     * @param FunctionalitiesOrdering $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $functionalityRepo = $this->entityManager->getRepository(Functionality::class);
        $statusRepo = $this->entityManager->getRepository(FunctionalityStatus::class);
        foreach ($data->getOrderingCollections() as $orderingCollection) {
            $foundStatus = $statusRepo->findOneBy(['id' => $orderingCollection->getStatusId()]);
            if (null !== $foundStatus) {
                foreach ($orderingCollection->getFunctionalities() as $key => $functionality) {
                    $foundFunctionality = $functionalityRepo->findOneBy(['id' => $functionality]);
                    if (null !== $foundFunctionality) {
                        $foundFunctionality->setFunctionalityStatus($foundStatus);
                        $foundFunctionality->setOrderNumber($key + 1);
                    }
                }
            }
        }

        try {
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}
