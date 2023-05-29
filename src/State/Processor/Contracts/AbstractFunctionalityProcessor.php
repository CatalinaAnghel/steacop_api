<?php
declare(strict_types=1);

namespace App\State\Processor\Contracts;

use ApiPlatform\State\ProcessorInterface;
use App\Dto\Functionality\Output\FunctionalityCharacteristicOutputDto;
use App\Entity\Functionality;
use App\Entity\FunctionalityStatus;
use App\Entity\FunctionalityType;
use App\Entity\Project;
use App\Entity\ProjectFunctionalitiesHistory;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Configuration\MappingInterface;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractFunctionalityProcessor implements ProcessorInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    protected function createHistory(FunctionalityStatus $status, Project $project, \DateTime $date): ProjectFunctionalitiesHistory
    {
        $functionalityRepo = $this->entityManager->getRepository(Functionality::class);
        $numberOfFunctionalities = $functionalityRepo->count([
            'functionalityStatus' => $status->getId(),
            'project' => $project->getId()
        ]);
        $history = new ProjectFunctionalitiesHistory();
        $history->setProject($project)
            ->setStatus($status)
            ->setCreatedAt(\DateTimeImmutable::createFromMutable($date))
            ->setUpdatedAt($date)
            ->setNumberOfFunctionalities($numberOfFunctionalities);
        return $history;
    }

    protected function addCommonOutputMapping(MappingInterface $mapping): void{
        $mapping->forMember(
            'type',
            function (Functionality $functionality): FunctionalityCharacteristicOutputDto {
                $typeConfig = new AutoMapperConfig();
                $typeConfig->registerMapping(
                    FunctionalityType::class,
                    FunctionalityCharacteristicOutputDto::class
                );
                return (new AutoMapper($typeConfig))
                    ->map($functionality->getType(), FunctionalityCharacteristicOutputDto::class);
            })
            ->forMember(
                'status',
                function (Functionality $functionality): FunctionalityCharacteristicOutputDto {
                    $statusConfig = new AutoMapperConfig();
                    $statusConfig->registerMapping(
                        FunctionalityStatus::class,
                        FunctionalityCharacteristicOutputDto::class
                    );
                    return (new AutoMapper($statusConfig))
                        ->map(
                            $functionality->getFunctionalityStatus(),
                            FunctionalityCharacteristicOutputDto::class
                        );
                })
            ->forMember('projectId', function (Functionality $functionality): int {
                return $functionality->getProject()?->getId();
            });
    }
}
