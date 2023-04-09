<?php
declare(strict_types=1);

namespace App\State\Processor\Assignment;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Assignment\Input\PatchAssignmentInputDto;
use App\Dto\Assignment\Output\AssignmentOutputDto;
use App\Entity\Assignment;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PatchAssignmentStateProcessor implements ProcessorInterface {
    public function __construct(
    private readonly EntityManagerInterface $entityManager,
    private readonly LoggerInterface $logger) {
    }

    /**
     * @inheritDoc
     * @param PatchAssignmentInputDto $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []):
    ?AssignmentOutputDto {
        $assignmentRepo = $this->entityManager->getRepository(Assignment::class);
        $assignment = $assignmentRepo->findOneBy(['id' => $uriVariables['id']]);
        if (null !== $assignment) {
            $assignment->setGrade($data->getGrade());

            if (!empty($data->getTitle())){
                $assignment->setTitle($data->getTitle());
            }

            if (!empty($data->getDescription())){
                $assignment->setDescription($data->getDescription());
            }

            if(null !== $data->getTurnedInDate()){
                $assignment->setTurnedInDate($data->getTurnedInDate());
            }

            $assignment->setUpdatedAt(new \DateTime('Now'));
            $this->entityManager->persist($assignment);

            try {
                $this->entityManager->flush();

                $configOutput = new AutoMapperConfig();
                $configOutput->registerMapping(
                    Assignment::class,
                    AssignmentOutputDto::class);
                $mapper = new AutoMapper($configOutput);
                /**
                 * @var AssignmentOutputDto $assignmentDto
                 */
                $assignmentDto = $mapper->map($assignment, AssignmentOutputDto::class);
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }

        return $assignmentDto ?? null;
    }
}
