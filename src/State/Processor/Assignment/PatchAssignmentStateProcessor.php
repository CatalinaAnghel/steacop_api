<?php
declare(strict_types=1);

namespace App\State\Processor\Assignment;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Assignment\Input\PatchAssignmentInputDto;
use App\Dto\Assignment\Output\AssignmentOutputDto;
use App\Dto\Document\Output\DocumentOutputDto;
use App\Entity\Assignment;
use App\Entity\Document;
use App\Message\Command\Assignment\UpdateAssignmentCommand;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PatchAssignmentStateProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $commandBus
    ) {
        date_default_timezone_set('Europe/Bucharest');
    }

    /**
     * @inheritDoc
     * @param PatchAssignmentInputDto $data
     */
    public function process(
        mixed $data, Operation $operation, array $uriVariables = [], array $context = []
    ): ?AssignmentOutputDto {
        $assignmentRepo = $this->entityManager->getRepository(Assignment::class);
        $assignment = $assignmentRepo->findOneBy(['id' => $uriVariables['id']]);
        if (null !== $assignment) {
            try {
                $this->commandBus->dispatch(
                    new UpdateAssignmentCommand(
                        $data,
                        $assignment,
                        $assignment->getProject()
                    )
                );

                $configOutput = new AutoMapperConfig();
                $configOutput->registerMapping(
                    Assignment::class,
                    AssignmentOutputDto::class
                )
                    ->forMember('documents', function (Assignment $source): array {
                        $documentConfig = new AutoMapperConfig();
                        $documentConfig->registerMapping(
                            Document::class,
                            DocumentOutputDto::class
                        )->forMember('contentUrl', function (Document $document) {
                            return '/documents/assignments/' . $document->getAssignment()?->getId() .
                                '/' . $document->getFilePath();
                        });
                        return (new AutoMapper($documentConfig))->mapMultiple(
                            $source->getDocuments(),
                            DocumentOutputDto::class
                        );
                    });
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
