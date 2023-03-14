<?php
declare(strict_types=1);

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\CustomSupervisoryPlan\Output\PlanOutputDto;
use App\Entity\CustomSupervisoryPlan;
use App\Entity\Project;
use App\Entity\Student;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * GetProjectSupervisoryPlanProvider
 */
class GetProjectSupervisoryPlanProvider implements ProviderInterface {
    /**
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly LoggerInterface        $logger) {
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null {
        $planDto = null;
        if (isset($uriVariables['projectId'])) {
            $projectRepo = $this->entityManager->getRepository(Project::class);
            $project = $projectRepo->findOneBy(['id' => (int)$uriVariables['projectId']]);
            if ($project !== null) {
                // the project exists
                $repo = $this->entityManager->getRepository(CustomSupervisoryPlan::class);
                $plan = $repo->findOneBy(['project' => (int)$uriVariables['projectId']]);
                try {
                    $planDto = $this->handleGetRequest($uriVariables, $project, $plan);
                } catch (\Exception $exception) {
                    $this->logger->error($exception->getMessage());
                }

            }
        }

        return $planDto;
    }

    /**
     * @param array $uriVariables
     * @param Project $project
     * @param CustomSupervisoryPlan|null $plan
     * @return PlanOutputDto|null
     * @throws UnregisteredMappingException
     */
    private function handleGetRequest(
        array $uriVariables,
        Project $project,
        CustomSupervisoryPlan|null $plan
    ): PlanOutputDto|null {
        if ($plan === null) {
            $studentRepo = $this->entityManager->getRepository(Student::class);
            $student = $studentRepo->findOneBy(['project' => $uriVariables['projectId']]);
            $supervisor = $project->getSupervisor();
            $studentSupervisoryPlan = $student?->getSupervisoryPlan();
            $supervisorPlan = $supervisor?->getSupervisoryPlan();
            if ($supervisorPlan === null || $studentSupervisoryPlan === null) {
                return null;
            }
            $planDto = new PlanOutputDto();
            if ($studentSupervisoryPlan->getId() !== $supervisorPlan->getId()) {
                $meanAssignmentsNumber = ($studentSupervisoryPlan->getNumberOfAssignments() +
                        $supervisorPlan->getNumberOfAssignments()) / 2;
                $meanMeetingsNumber = ($studentSupervisoryPlan->getNumberOfGuidanceMeetings() +
                        $supervisorPlan->getNumberOfGuidanceMeetings()) / 2;
                $planDto->setNumberOfAssignments((int)$meanAssignmentsNumber);
                $planDto->setNumberOfGuidanceMeetings((int)$meanMeetingsNumber);
            } else {
                $planDto->setNumberOfGuidanceMeetings($studentSupervisoryPlan->getNumberOfGuidanceMeetings());
                $planDto->setNumberOfAssignments($studentSupervisoryPlan->getNumberOfAssignments());
            }
            $planDto->setSuggested(true);
        } else {
            $config = new AutoMapperConfig();
            $config->registerMapping(CustomSupervisoryPlan::class,
                PlanOutputDto::class
            );
            $mapper = new AutoMapper($config);
            $planDto = $mapper->map($plan, PlanOutputDto::class);
            /**
             * @var PlanOutputDto $planDto
             */
            $planDto->setSuggested(false);
        }

        return $planDto;
    }
}
