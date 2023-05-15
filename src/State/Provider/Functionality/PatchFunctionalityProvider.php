<?php
declare(strict_types=1);

namespace App\State\Provider\Functionality;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Functionality\Input\PatchFunctionalityInputDto;
use App\Dto\FunctionalityAttachment\Output\FunctionalityAttachmentOutputDto;
use App\Entity\Functionality;
use App\Entity\FunctionalityAttachment;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PatchFunctionalityProvider implements ProviderInterface
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
        $functionalityDto = null;
        if (isset($uriVariables['id'])) {
            $functionalityRepo = $this->entityManager->getRepository(Functionality::class);
            $functionality = $functionalityRepo->findOneBy(['id' => (int)$uriVariables['id']]);
            if ($functionality !== null) {
                $config = new AutoMapperConfig();
                $config->registerMapping(Functionality::class,
                    PatchFunctionalityInputDto::class
                )->forMember('functionalityAttachments', function (Functionality $source): array {
                    $documentConfig = new AutoMapperConfig();
                    $documentConfig->registerMapping(
                        FunctionalityAttachment::class,
                        FunctionalityAttachmentOutputDto::class
                    );
                    return (new AutoMapper($documentConfig))->mapMultiple(
                        $source->getFunctionalityAttachments(),
                        FunctionalityAttachmentOutputDto::class
                    );
                })->forMember('status', function(Functionality $source): int{
                    return $source->getFunctionalityStatus()?->getId();
                })->forMember('type', function(Functionality $source): int{
                    return $source->getType()?->getId();
                });
                $mapper = new AutoMapper($config);
                try {
                    $functionalityDto = $mapper->map($functionality, PatchFunctionalityInputDto::class);

                } catch (UnregisteredMappingException $exception) {
                    $this->logger->error($exception->getMessage());
                }
            }
        }
        return $functionalityDto;
    }
}
