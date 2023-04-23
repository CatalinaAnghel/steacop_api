<?php
declare(strict_types=1);

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\ProjectSupervisoryPlan;
use App\Dto\CustomSupervisoryPlan\Output\PlanOutputDto;
use App\Entity\CustomSupervisoryPlan;
use App\Entity\Project;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ProjectSupervisoryPlanProcessor implements ProcessorInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly LoggerInterface        $logger) {}

    /**
     * @param $data
     * @inheritDoc
     * @throws UnregisteredMappingException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $repo = $this->entityManager->getRepository(CustomSupervisoryPlan::class);
        if ($operation->getMethod() === 'POST') {
            $projectId = $data->getProjectId();
        } else {
            $projectId = $uriVariables['projectId'];
        }
        $plan = $repo->findOneBy(['project' => (int)$projectId]);
        if ($plan === null) {
            $plan = $this->createPlan($data);
        } else {
            $plan->setNumberOfAssignments($data->getNumberOfAssignments());
            $plan->setNumberOfGuidanceMeetings($data->getNumberOfGuidanceMeetings());
            $this->entityManager->flush();
        }

        if ($plan !== null) {
            $config = new AutoMapperConfig();
            $config->registerMapping(CustomSupervisoryPlan::class,
                PlanOutputDto::class
            );
            $mapper = new AutoMapper($config);
            $planDto = $mapper->map($plan, PlanOutputDto::class);
        }

        return $planDto ?? null;
    }

    /**
     * @param ProjectSupervisoryPlan $data
     * @return PlanOutputDto|null
     */
    private function createPlan(ProjectSupervisoryPlan $data): CustomSupervisoryPlan|null
    {
        $projectRepo = $this->entityManager->getRepository(Project::class);
        $project = $projectRepo->findOneBy(['id' => $data->getProjectId()]);
        if ($project !== null) {
            $plan = new CustomSupervisoryPlan();
            $plan->setNumberOfGuidanceMeetings($data->getNumberOfGuidanceMeetings());
            $plan->setNumberOfAssignments($data->getNumberOfAssignments());
            $plan->setProject($project);
            try {
                $this->entityManager->persist($plan);
                $this->entityManager->flush();
                return $plan;
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }
        return null;
    }
}
