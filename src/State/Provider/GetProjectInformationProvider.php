<?php
declare(strict_types=1);

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use App\Dto\Project\Output\ProjectInformationOutputDto;
use App\Entity\Assignment;
use App\Entity\GuidanceMeeting;
use App\Entity\MilestoneMeeting;
use App\Entity\Project;
use App\State\Provider\Contracts\AbstractPlanProvider;
use Doctrine\ORM\EntityManagerInterface;

class GetProjectInformationProvider extends AbstractPlanProvider {
    public function __construct(private readonly EntityManagerInterface $entityManager) {
        parent::__construct($this->entityManager);
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null {
        $projectDto = null;
        if (isset($uriVariables['id'])) {
            $projectRepository = $this->entityManager->getRepository(Project::class);
            $project = $projectRepository->findOneBy(['id' => $uriVariables['id']]);
            if (null !== $project) {
                $projectSupervisoryPlan = $project->getSupervisoryPlan();
                $projectDto = new ProjectInformationOutputDto();
                if (null === $projectSupervisoryPlan) {
                    // the supervisory plan was not proposed, so the system suggestion will be taken into account
                    $projectSupervisoryPlan = $this->determineSupervisoryPlan($project);
                }

                if (null !== $projectSupervisoryPlan) {
                    $projectDto->setNumberOfAssignments($projectSupervisoryPlan->getNumberOfAssignments());
                    $projectDto->setNumberOfGuidanceMeetings($projectSupervisoryPlan->getNumberOfGuidanceMeetings());
                }

                $projectDto->setTitle($project->getTitle());
                $projectDto->setDescription($project->getDescription());
                $projectDto->setRepositoryUrl($project->getRepositoryUrl());
                $projectDto->setNumberOfMilestoneMeetings(3);

                $numberOfFinishedAssignments = count(array_filter($project->getAssignments()->getValues(),
                    static function (Assignment $assignment, int $key) {
                        return $assignment->getTurnedInDate() !== null;
                    }, ARRAY_FILTER_USE_BOTH));
                $projectDto->setNumberOfFinishedAssignments($numberOfFinishedAssignments);
                $numberOfCompletedGuidanceMeetings = count(array_filter($project->getGuidanceMeetings()->getValues(),
                    static function (GuidanceMeeting $guidanceMeeting, int $key) {
                        return $guidanceMeeting->isCompleted();
                    }, ARRAY_FILTER_USE_BOTH));
                $projectDto->setNumberOfCompletedGuidanceMeetings($numberOfCompletedGuidanceMeetings);
                $numberOfCompletedMilestoneMeetings = count(array_filter($project->getMilestoneMeetings()->getValues(),
                    static function (MilestoneMeeting $milestoneMeeting, int $key) {
                        return $milestoneMeeting->isCompleted();
                    }, ARRAY_FILTER_USE_BOTH));
                $projectDto->setNumberOfCompletedMilestoneMeetings($numberOfCompletedMilestoneMeetings);
            }
        }
        return $projectDto;
    }
}
