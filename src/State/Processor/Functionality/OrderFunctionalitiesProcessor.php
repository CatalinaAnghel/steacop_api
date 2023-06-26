<?php
declare(strict_types=1);

namespace App\State\Processor\Functionality;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\FunctionalitiesOrdering;
use App\Entity\Functionality;
use App\Entity\FunctionalityStatus;
use App\Entity\FunctionalityStatusHistory;
use App\Entity\FunctionalityType;
use App\Entity\Project;
use App\Entity\ProjectFunctionalitiesHistory;
use App\Entity\Student;
use App\Entity\User;
use App\Helper\FunctionalityTypesHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;

class OrderFunctionalitiesProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Security               $security,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $logger
    ) {
        date_default_timezone_set('Europe/Bucharest');
    }

    /**
     * @inheritDoc
     * @param FunctionalitiesOrdering $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $functionalityRepo = $this->entityManager->getRepository(Functionality::class);
        $statusRepo = $this->entityManager->getRepository(FunctionalityStatus::class);
        $project = $this->getProject();
        if (null !== $project) {
            foreach ($data->getOrderingCollections() as $orderingCollection) {
                $foundStatus = $statusRepo->findOneBy(['id' => $orderingCollection->getStatusId()]);

                if (null !== $foundStatus) {
                    foreach ($orderingCollection->getFunctionalities() as $key => $functionality) {
                        $foundFunctionality = $functionalityRepo->findOneBy(['id' => $functionality]);
                        if (null !== $foundFunctionality) {
                            if ($foundStatus->getId() !== $foundFunctionality->getFunctionalityStatus()?->getId()) {
                                // log the update of the status of the functionality
                                $functionalityLog = new FunctionalityStatusHistory();
                                $functionalityLog->setCreatedAt(new \DateTimeImmutable('Now'))
                                    ->setFunctionality($foundFunctionality)
                                    ->setOldStatus($foundFunctionality->getFunctionalityStatus())
                                    ->setNewStatus($foundStatus);
                                $this->entityManager->persist($functionalityLog);
                            }
                            $foundFunctionality->setFunctionalityStatus($foundStatus);
                            $foundFunctionality->setOrderNumber($key + 1);
                            $foundFunctionality->setUpdatedAt(new \DateTime('Now'));
                            
                        }
                    }
                    $this->entityManager->flush();

                    $epicType = $this->entityManager->getRepository(FunctionalityType::class)->findOneBy(['name' =>FunctionalityTypesHelper::Epic->value]);
                    $history = $this->createHistory(
                        $project,
                        $foundStatus,
                        count($orderingCollection->getFunctionalities()) + $this->entityManager
                        ->getRepository(Functionality::class)->count([
                            'project' => $project,
                            'type'=> $epicType, 
                            'functionalityStatus' => $foundStatus
                            ])
                    );
                    $this->entityManager->persist($history);
                }
            }
        }

        try {
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * @param Project $project
     * @param FunctionalityStatus $status
     * @param int $numberOfFunctionalities
     * @return ProjectFunctionalitiesHistory
     */
    private function createHistory(Project $project, FunctionalityStatus $status, int $numberOfFunctionalities): ProjectFunctionalitiesHistory
    {
        $history = new ProjectFunctionalitiesHistory();
        return $history->setProject($project)
            ->setStatus($status)
            ->setCreatedAt(new \DateTimeImmutable('Now'))
            ->setUpdatedAt(new \DateTime('Now'))
            ->setNumberOfFunctionalities($numberOfFunctionalities);
    }

    /**
     * @return Project|null
     */
    private function getProject(): Project|null
    {
        $studentRepo = $this->entityManager->getRepository(Student::class);
        $userRepo = $this->entityManager->getRepository(User::class);
        $user = $userRepo->findOneBy(['email' => $this->security->getUser()?->getUserIdentifier()]);
        $student = $studentRepo->findOneBy(['user' => $user?->getId()]);

        return $student?->getProject();
    }
}
