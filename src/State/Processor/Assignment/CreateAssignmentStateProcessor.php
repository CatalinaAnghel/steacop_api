<?php
declare(strict_types=1);

namespace App\State\Processor\Assignment;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Assignment\Input\CreateAssignmentInputDto;
use App\Dto\Assignment\Output\AssignmentOutputDto;
use App\Dto\Document\Output\DocumentOutputDto;
use App\Entity\Assignment;
use App\Entity\Document;
use App\Entity\Project;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateAssignmentStateProcessor implements ProcessorInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly LoggerInterface        $logger)
    {
        date_default_timezone_set('Europe/Bucharest');
    }

    /**
     * @inheritDoc
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []):
    ?AssignmentOutputDto
    {
        $projectRepo = $this->entityManager->getRepository(Project::class);
        $project = $projectRepo->findOneBy(['id' => $data->getProjectId()]);
        if (null !== $project) {
            try {
                $config = new AutoMapperConfig();
                $config->registerMapping(CreateAssignmentInputDto::class,
                    Assignment::class);
                $mapper = new AutoMapper($config);
                /**
                 * @var Assignment $assignment
                 */
                $assignment = $mapper->map($data, Assignment::class);
                $assignment->setCreatedAt(new \DateTimeImmutable('Now'));
                $assignment->setUpdatedAt(new \DateTime('Now'));
                $assignment->setProject($project);
                $this->entityManager->persist($assignment);
                $this->entityManager->flush();

                $configOutput = new AutoMapperConfig();
                $configOutput->registerMapping(
                    Assignment::class,
                    AssignmentOutputDto::class
                )->forMember('documents', function (): array {
                    return [];
                });
                $mapper = new AutoMapper($configOutput);

                /**
                 * @var AssignmentOutputDto $assignmentDto
                 */
                $assignmentDto = $mapper->map($assignment, AssignmentOutputDto::class);
                if (null !== $assignment->getTurnedInDate()) {
                    $assignmentDto->setTurnedInDate(
                        new \DateTime(($assignment->getTurnedInDate())?->format('Y-m-d H:i:s')));
                }
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        } else {
            throw new NotFoundHttpException('The project could not be found');
        }

        return $assignmentDto ?? null;
    }
}
