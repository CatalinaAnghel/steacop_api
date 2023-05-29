<?php
declare(strict_types=1);

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Project\Input\PatchProjectInputDto;
use App\Entity\Project;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PatchProjectItemProvider implements ProviderInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $logger
    ) {}

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $projectDto = null;
        if (isset($uriVariables['id'])) {
            $projectRepo = $this->entityManager->getRepository(Project::class);
            $project = $projectRepo->findOneBy(['id' => (int)$uriVariables['id']]);
            if ($project !== null) {
                $config = new AutoMapperConfig();
                $config->registerMapping(Project::class,
                    PatchProjectInputDto::class
                );
                $mapper = new AutoMapper($config);
                try {
                    $projectDto = $mapper->map($project, PatchProjectInputDto::class);

                } catch (UnregisteredMappingException $exception) {
                    $this->logger->error($exception->getMessage());
                }
            }
        }
        return $projectDto;
    }
}
