<?php
declare(strict_types=1);

namespace App\State\Provider\Functionality;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Functionality\Output\BaseFunctionalityOutputDto;
use App\Dto\Functionality\Output\FunctionalityCharacteristicOutputDto;
use App\Dto\Functionality\Output\FunctionalityOutputDto;
use App\Dto\FunctionalityAttachment\Output\FunctionalityAttachmentOutputDto;
use App\Entity\Functionality;
use App\Entity\FunctionalityAttachment;
use App\Entity\FunctionalityStatus;
use App\Entity\FunctionalityType;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Psr\Log\LoggerInterface;

class GetFunctionalityItemProvider implements ProviderInterface
{
    public function __construct(private readonly ProviderInterface $decoratedProvider,
                                private readonly LoggerInterface   $logger)
    {
        date_default_timezone_set('Europe/Bucharest');
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $functionality = $this->decoratedProvider->provide($operation, $uriVariables, $context);
        if (null !== $functionality) {
            $configOutput = new AutoMapperConfig();
            $configOutput->registerMapping(
                Functionality::class,
                FunctionalityOutputDto::class
            )
                ->forMember('functionalityAttachments', function (Functionality $source): array {
                    $documentConfig = new AutoMapperConfig();
                    $documentConfig->registerMapping(
                        FunctionalityAttachment::class,
                        FunctionalityAttachmentOutputDto::class
                    )->forMember('contentUrl', function (FunctionalityAttachment $document) {
                        return '/documents/functionalities/' . $document->getFunctionality()?->getId() .
                            '/' . $document->getFilePath();
                    });
                    return (new AutoMapper($documentConfig))->mapMultiple(
                        $source->getFunctionalityAttachments(),
                        FunctionalityAttachmentOutputDto::class
                    );
                })
                ->forMember('type', function (Functionality $functionality): FunctionalityCharacteristicOutputDto {
                    $typeConfig = new AutoMapperConfig();
                    $typeConfig->registerMapping(
                        FunctionalityType::class,
                        FunctionalityCharacteristicOutputDto::class
                    )
                        ->forMember('name', function (FunctionalityType $type): string {
                            return $type->getName();
                        });
                    return (new AutoMapper($typeConfig))->map(
                        $functionality->getType(),
                        FunctionalityCharacteristicOutputDto::class
                    );
                })
                ->forMember('status', function (Functionality $functionality): FunctionalityCharacteristicOutputDto {
                    $statusConfig = new AutoMapperConfig();
                    $statusConfig->registerMapping(
                        FunctionalityStatus::class,
                        FunctionalityCharacteristicOutputDto::class
                    )
                        ->forMember('name', function (FunctionalityStatus $status): string {
                            return $status->getName();
                        });
                    return (new AutoMapper($statusConfig))->map(
                        $functionality->getFunctionalityStatus(),
                        FunctionalityCharacteristicOutputDto::class
                    );
                })
                ->forMember('projectId', function (Functionality $functionality): int {
                    return $functionality->getProject()?->getId();
                })
                ->forMember('code', function (Functionality $functionality): string {
                    return $functionality->getProject()?->getCode() . '-' . $functionality->getCode();
                })
                ->forMember(
                    'parent',
                    function (Functionality $functionality): ?BaseFunctionalityOutputDto {
                        $parentFunctionality = $functionality->getParentFunctionality();
                        if (null !== $parentFunctionality) {
                            $parent = new BaseFunctionalityOutputDto();
                            $parent->setId($parentFunctionality->getId());
                            $parent->setCode($functionality->getProject()?->getCode() . '-' .
                                (string)$parentFunctionality->getCode());
                            $parent->setTitle($parentFunctionality->getTitle());
                        }
                        return $parent ?? null;
                    });
            $mapper = new AutoMapper($configOutput);

            try {
                /**
                 * @var FunctionalityOutputDto $functionalityDto
                 */
                $functionalityDto = $mapper->map($functionality, FunctionalityOutputDto::class);
            } catch (UnregisteredMappingException $e) {
                $this->logger->error($e->getMessage());
            }
        }

        return $functionalityDto ?? null;
    }
}
