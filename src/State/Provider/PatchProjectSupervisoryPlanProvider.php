<?php
declare(strict_types=1);

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\CustomSupervisoryPlan\Input\PlanInputDto;
use App\Entity\CustomSupervisoryPlan;
use App\Entity\Project;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PatchProjectSupervisoryPlanProvider implements ProviderInterface
{

    /**
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly LoggerInterface        $logger) {}

    /**
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return object|array|null
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $planDto = null;
        if (isset($uriVariables['projectId'])) {
            $projectRepo = $this->entityManager->getRepository(Project::class);
            $project = $projectRepo->findOneBy(['id' => (int)$uriVariables['projectId']]);
            if ($project !== null) {
                // the project exists
                $repo = $this->entityManager->getRepository(CustomSupervisoryPlan::class);
                $plan = $repo->findOneBy(['project' => (int)$uriVariables['projectId']]);
                if ($plan !== null) {
                    $config = new AutoMapperConfig();
                    $config->registerMapping(CustomSupervisoryPlan::class,
                        PlanInputDto::class
                    );
                    $mapper = new AutoMapper($config);
                    try {
                        $planDto = $mapper->map($plan, PlanInputDto::class);

                    } catch (UnregisteredMappingException $exception) {
                        $this->logger->error($exception->getMessage());
                    }
                }
            }
        }

        return $planDto;
    }
}
