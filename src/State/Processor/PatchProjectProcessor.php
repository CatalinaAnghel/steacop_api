<?php
declare(strict_types=1);

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Project\Input\PatchProjectInputDto;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PatchProjectProcessor implements ProcessorInterface
{

    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly LoggerInterface        $logger,
    ) {}

    /**
     * @inheritDoc
     * @param PatchProjectInputDto $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []):
    ?Project
    {
        $projectRepo = $this->entityManager->getRepository(Project::class);
        $project = $projectRepo->findOneBy(['id' => $uriVariables['id']]);
        if (null !== $project) {
            if (null !== $data->getDescription()) {
                $project->setDescription($data->getDescription());
            }

            if (null !== $data->getTitle()) {
                $project->setTitle($data->getTitle());
            }

            if (null !== $data->getGrade()) {
                $project->setGrade($data->getGrade());
            }
            if (null !== $data->getRepositoryUrl()) {
                $project->setRepositoryUrl($data->getRepositoryUrl());
            }
            $this->entityManager->persist($project);

            try {
                $this->entityManager->flush();
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }

        return $project;
    }
}
