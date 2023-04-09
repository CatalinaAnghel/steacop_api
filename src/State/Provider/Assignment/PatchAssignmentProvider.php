<?php
declare(strict_types=1);

namespace App\State\Provider\Assignment;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Assignment\Input\PatchAssignmentInputDto;
use App\Dto\Document\Output\DocumentOutputDto;
use App\Entity\Assignment;
use App\Entity\Document;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PatchAssignmentProvider implements ProviderInterface {
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null {
        $assignmentDto = null;
        if (isset($uriVariables['id'])) {
            $assignmentRepo = $this->entityManager->getRepository(Assignment::class);
            $assignment = $assignmentRepo->findOneBy(['id' => (int)$uriVariables['id']]);
            if ($assignment !== null) {
                $config = new AutoMapperConfig();
                $config->registerMapping(Assignment::class,
                    PatchAssignmentInputDto::class
                )->forMember('documents', function (Assignment $source): array {
                    $documentConfig = new AutoMapperConfig();
                    $documentConfig->registerMapping(
                        Document::class,
                        DocumentOutputDto::class
                    );
                    return (new AutoMapper($documentConfig))->mapMultiple(
                        $source->getDocuments(),
                        DocumentOutputDto::class
                    );
                });
                $mapper = new AutoMapper($config);
                try{
                    $assignmentDto = $mapper->map($assignment, PatchAssignmentInputDto::class);

                } catch (UnregisteredMappingException $exception) {
                    $this->logger->error($exception->getMessage());
                }
            }
        }
        return $assignmentDto;
    }
}
