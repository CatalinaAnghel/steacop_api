<?php
declare(strict_types=1);

namespace App\State\Provider\Functionality;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Functionality\Output\FunctionalityCharacteristicOutputDto;
use App\Dto\Functionality\Output\FunctionalityOutputDto;
use App\Dto\FunctionalityAttachment\Output\FunctionalityAttachmentOutputDto;
use App\Entity\Functionality;
use App\Entity\FunctionalityAttachment;
use App\Entity\FunctionalityStatus;
use App\Entity\FunctionalityType;
use App\Paginator\StatePaginator;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;

class GetFunctionalityCollectionProvider implements ProviderInterface
{
    public function __construct(private readonly ProviderInterface $decoratedProvider,
                                private readonly Pagination        $pagination)
    {
        date_default_timezone_set('Europe/Bucharest');
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $functionalities = $this->decoratedProvider->provide($operation, $uriVariables, $context);
        $config = new AutoMapperConfig();
        $config
            ->registerMapping(Functionality::class, FunctionalityOutputDto::class)
            ->forMember('functionalityAttachments', function (Functionality $source): array {
                $attachmentConfig = new AutoMapperConfig();
                $attachmentConfig->registerMapping(
                    FunctionalityAttachment::class,
                    FunctionalityAttachmentOutputDto::class
                )
                    ->forMember('contentUrl', function (FunctionalityAttachment $attachment) {
                        return '/documents/functionalities/' . $attachment->getFunctionality()?->getId() .
                            '/' . $attachment->getFilePath();
                    });
                return (new AutoMapper($attachmentConfig))->mapMultiple(
                    $source->getFunctionalityAttachments(),
                    FunctionalityAttachmentOutputDto::class
                );
            })
            ->forMember('type', function (Functionality $functionality): FunctionalityCharacteristicOutputDto {
                $typeConfig = new AutoMapperConfig();
                $typeConfig->registerMapping(
                    FunctionalityType::class,
                    FunctionalityCharacteristicOutputDto::class
                )->forMember('name', function (FunctionalityType $type): string {
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
                )->forMember('name', function (FunctionalityStatus $status): string {
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
            });
        $mapper = new AutoMapper($config);
        $functionalitiesCollection = $mapper->mapMultiple($functionalities, FunctionalityOutputDto::class);

        $functionalitiesIterator = new \ArrayIterator($functionalitiesCollection);

        [$page, $offset, $limit] = $this->pagination->getPagination(
            $operation, $context);
        $max = count($functionalitiesCollection);
        return new StatePaginator($functionalitiesIterator, $page, $limit, $max);
    }
}
