<?php
declare(strict_types=1);

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use App\Dto\CustomSupervisoryPlan\Output\PlanOutputDto;
use App\Entity\CustomSupervisoryPlan;
use App\Entity\Project;
use App\State\Provider\Contracts\AbstractPlanProvider;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * GetProjectSupervisoryPlanProvider
 */
class GetProjectSupervisoryPlanProvider extends AbstractPlanProvider
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly LoggerInterface        $logger)
    {
        parent::__construct($this->entityManager);
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $planDto = null;
        if (isset($uriVariables['projectId'])) {
            $projectRepo = $this->entityManager->getRepository(Project::class);
            $project = $projectRepo->findOneBy(['id' => (int)$uriVariables['projectId']]);
            if (null !== $project) {
                // the project exists
                $repo = $this->entityManager->getRepository(CustomSupervisoryPlan::class);
                $plan = $repo->findOneBy(['project' => (int)$uriVariables['projectId']]);
                try {
                    $planDto = $this->handleGetRequest($project, $plan);
                } catch (\Exception $exception) {
                    $this->logger->error($exception->getMessage());
                }

            }
        }

        return $planDto;
    }

    /**
     * @param Project $project
     * @param CustomSupervisoryPlan|null $plan
     * @return PlanOutputDto|null
     * @throws UnregisteredMappingException
     */
    private function handleGetRequest(
        Project                    $project,
        CustomSupervisoryPlan|null $plan
    ): PlanOutputDto|null
    {
        if (null === $plan) {
            $computedPlan = $this->determineSupervisoryPlan($project);
            if (null === $computedPlan) {
                return null;
            }
            $planDto = new PlanOutputDto();
            $planDto->setNumberOfGuidanceMeetings($computedPlan->getNumberOfGuidanceMeetings());
            $planDto->setNumberOfAssignments($computedPlan->getNumberOfAssignments());
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
