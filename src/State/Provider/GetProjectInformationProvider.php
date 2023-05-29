<?php
declare(strict_types=1);

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use App\Dto\Project\Output\AssignmentInformation;
use App\Dto\Project\Output\MeetingInformation;
use App\Dto\Project\Output\ProjectInformationOutputDto;
use App\Entity\Assignment;
use App\Entity\GuidanceMeeting;
use App\Entity\MilestoneMeeting;
use App\Entity\Project;
use App\Entity\SystemSetting;
use App\Helper\SystemSettingsHelper;
use App\State\Provider\Contracts\AbstractPlanProvider;
use Doctrine\ORM\EntityManagerInterface;

class GetProjectInformationProvider extends AbstractPlanProvider
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        parent::__construct($this->entityManager);
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $projectDto = null;
        if (isset($uriVariables['id'])) {
            $projectRepository = $this->entityManager->getRepository(Project::class);
            $project = $projectRepository->findOneBy(['id' => $uriVariables['id']]);
            if (null !== $project) {
                $projectSupervisoryPlan = $project->getSupervisoryPlan();
                if (null === $projectSupervisoryPlan) {
                    // the supervisory plan was not proposed, so the system suggestion will be taken into account
                    $projectSupervisoryPlan = $this->determineSupervisoryPlan($project);
                }

                $assignmentInfo = $this->buildAssignmentInfo(
                    null !== $projectSupervisoryPlan? $projectSupervisoryPlan->getNumberOfAssignments(): 3,
                    $project
                );
                $milestoneMeetingInfo = $this->buildMilestoneMeetingInfo($project);
                $guidanceMeetingInfo = $this->buildGuidanceMeetingInfo(
                    null !== $projectSupervisoryPlan? $projectSupervisoryPlan->getNumberOfGuidanceMeetings(): 3,
                    $project
                );

                $projectDto = new ProjectInformationOutputDto();
                $projectDto->setGrade($project->getGrade());
                $projectDto->setTitle($project->getTitle());
                $projectDto->setDescription($project->getDescription());
                $projectDto->setRepositoryUrl($project->getRepositoryUrl());
                $projectDto->setAssignmentInformation($assignmentInfo);
                $projectDto->setMilestoneMeetingInformation($milestoneMeetingInfo);
                $projectDto->setGuidanceMeetingInformation($guidanceMeetingInfo);
            }
        }
        return $projectDto;
    }

    private function buildAssignmentInfo(int $total, Project $project): AssignmentInformation{
        $assignmentInfo = new AssignmentInformation();

        $numberOfFinishedAssignments = count(array_filter($project->getAssignments()->getValues(),
            static function (Assignment $assignment, int $key) {
                return $assignment->getTurnedInDate() !== null;
            }, ARRAY_FILTER_USE_BOTH));
        $assignmentInfo->setCompleted($numberOfFinishedAssignments);
        $assignmentInfo->setTotal($total);

        return $assignmentInfo;
    }

    private function buildMilestoneMeetingInfo(Project $project): MeetingInformation{
        $numberOfRequiredMilestoneMeetings = (int)$this->entityManager->getRepository(SystemSetting::class)
            ->findOneBy(['name' => SystemSettingsHelper::MilestoneMeetingsLimit->getName()])?->getValue();

        $numberOfCompletedMilestoneMeetings = count(array_filter($project->getMilestoneMeetings()->getValues(),
            static function (MilestoneMeeting $milestoneMeeting, int $key) {
                return $milestoneMeeting->isCompleted();
            }, ARRAY_FILTER_USE_BOTH));

        $numberOfMissedMilestoneMeetings = count(array_filter($project->getMilestoneMeetings()->getValues(),
            static function (MilestoneMeeting $milestoneMeeting, int $key) {
                return $milestoneMeeting->isMissed();
            }, ARRAY_FILTER_USE_BOTH));

        $milestoneMeetingInfo = new MeetingInformation();
        $milestoneMeetingInfo->setTotal($numberOfRequiredMilestoneMeetings);
        $milestoneMeetingInfo->setCompleted($numberOfCompletedMilestoneMeetings);
        $milestoneMeetingInfo->setMissed($numberOfMissedMilestoneMeetings);

        return $milestoneMeetingInfo;
    }

    private function buildGuidanceMeetingInfo(int $total, Project $project): MeetingInformation{
        $numberOfCompletedGuidanceMeetings = count(array_filter($project->getGuidanceMeetings()->getValues(),
            static function (GuidanceMeeting $guidanceMeeting, int $key) {
                return $guidanceMeeting->isCompleted();
            }, ARRAY_FILTER_USE_BOTH));

        $numberOfMissedGuidanceMeetings = count(array_filter($project->getGuidanceMeetings()->getValues(),
            static function (GuidanceMeeting $guidanceMeeting, int $key) {
                return $guidanceMeeting->isMissed();
            }, ARRAY_FILTER_USE_BOTH));


        $guidanceMeetingInfo = new MeetingInformation();
        $guidanceMeetingInfo->setTotal($total);
        $guidanceMeetingInfo->setCompleted($numberOfCompletedGuidanceMeetings);
        $guidanceMeetingInfo->setMissed($numberOfMissedGuidanceMeetings);

        return $guidanceMeetingInfo;
    }
}
