<?php
declare(strict_types=1);

namespace App\State\Provider\Assignment;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Assignment\Output\AssignmentOutputDto;
use App\Dto\Document\Output\DocumentOutputDto;
use App\Entity\Assignment;
use App\Entity\Document;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Psr\Log\LoggerInterface;

class GetAssignmentItemProvider implements ProviderInterface {
    public function __construct(private readonly ProviderInterface $decoratedProvider,
                                private readonly LoggerInterface   $logger) {
        date_default_timezone_set('Europe/Bucharest');
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null {
        $assignment = $this->decoratedProvider->provide($operation, $uriVariables, $context);
        if (null !== $assignment) {
            $configOutput = new AutoMapperConfig();
            $configOutput->registerMapping(
                Assignment::class,
                AssignmentOutputDto::class
            )->forMember('documents', function (Assignment $source): array {
                $documentConfig = new AutoMapperConfig();
                $documentConfig->registerMapping(
                    Document::class,
                    DocumentOutputDto::class
                )->forMember('contentUrl', function (Document $document) {
                        return '/documents/assignments/' .
                            $document->getAssignment()?->getId() . '/' . $document->getFilePath();
                    });
                return (new AutoMapper($documentConfig))->mapMultiple(
                    $source->getDocuments(),
                    DocumentOutputDto::class
                );
            });
            $mapper = new AutoMapper($configOutput);

            try {
                /**
                 * @var AssignmentOutputDto $meetingDto
                 */
                $assignmentDto = $mapper->map($assignment, AssignmentOutputDto::class);
            } catch (UnregisteredMappingException $e) {
                $this->logger->error($e->getMessage());
            }
        }

        return $assignmentDto ?? null;
    }
}
