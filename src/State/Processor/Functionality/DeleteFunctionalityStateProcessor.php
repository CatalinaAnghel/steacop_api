<?php
declare(strict_types=1);

namespace App\State\Processor\Functionality;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Functionality;
use App\Entity\FunctionalityStatus;
use App\Entity\Project;
use App\Helper\FunctionalityStatusesHelper;
use App\State\Processor\Contracts\AbstractFunctionalityProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class DeleteFunctionalityStateProcessor extends AbstractFunctionalityProcessor
{
    public function __construct(
        private readonly ProcessorInterface     $decoratedProcessor,
        private readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct($this->entityManager);
    }

    /**
     * @inheritDoc
     * @param Functionality $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (FunctionalityStatusesHelper::Closed !== $data->getFunctionalityStatus()?->getName()) {
            // the assignment has not been turned in
            $projectId = $data->getProject()?->getId();
            $statusId = $data->getFunctionalityStatus()?->getId();

            if(null === $data->getOrderNumber()){
                $this->entityManager->getRepository(Functionality::class)->updateOrder(
                    $data->getOrderNumber(),
                    -1,
                    $data->getProject()?->getId(),
                    $data->getFunctionalityStatus()?->getId()
                );
            }

            $this->decoratedProcessor->process($data, $operation, $uriVariables, $context);

            $project = $this->entityManager->getRepository(Project::class)->findOneBy([
                'id' => $projectId
            ]);
            $status = $this->entityManager->getRepository(FunctionalityStatus::class)->findOneBy([
                'id' => $statusId
            ]);
            if (null !== $project && null !== $status) {
                $history = $this->createHistory(
                    $status,
                    $project,
                    new \DateTime('Now')
                );

                $this->entityManager->persist($history);
                $this->entityManager->flush();
            }
        } else {
            throw new UnprocessableEntityHttpException(
                'Cannot delete a functionality that has been marked as closed'
            );
        }
    }
}
