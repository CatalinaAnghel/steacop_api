<?php
declare(strict_types=1);

namespace App\State\Processor\Functionality;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Functionality\Input\PatchFunctionalityInputDto;
use App\Dto\Functionality\Output\FunctionalityCharacteristicOutputDto;
use App\Dto\Functionality\Output\FunctionalityOutputDto;
use App\Dto\FunctionalityAttachment\Output\FunctionalityAttachmentOutputDto;
use App\Entity\Functionality;
use App\Entity\FunctionalityAttachment;
use App\Entity\FunctionalityStatus;
use App\Entity\FunctionalityType;
use App\Helper\FunctionalityTypesHelper;
use App\Validator\Contracts\ValidatorInterface;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PatchFunctionalityStateProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $logger,
        private readonly ValidatorInterface     $functionalityValidator
    )
    {
        date_default_timezone_set('Europe/Bucharest');
    }

    /**
     * @inheritDoc
     * @param PatchFunctionalityInputDto $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []):
    ?FunctionalityOutputDto
    {
        $functionalityRepo = $this->entityManager->getRepository(Functionality::class);
        $functionality = $functionalityRepo->findOneBy(['id' => $uriVariables['id']]);
        if (null !== $functionality) {
            $this->functionalityValidator->validate($data, $functionality);
            /**
             * @var Functionality $functionality
             */
            $functionality->setTitle($data->getTitle());
            $functionality->setDescription($data->getDescription());
            if (null !== $data->getParentFunctionalityId()) {
                $parentFunctionality = $functionalityRepo->findOneBy(['id' => $data->getParentFunctionalityId()]);
                $functionality->setParentFunctionality($parentFunctionality);
            }
            $statusRepo = $this->entityManager->getRepository(FunctionalityStatus::class);
            $status = $statusRepo->findOneBy(['id' => $data->getStatus()]);
            $functionality->setFunctionalityStatus($status);

            $typeRepo = $this->entityManager->getRepository(FunctionalityType::class);
            $type = $typeRepo->findOneBy(['id' => $data->getType()]);
            $functionality->setType($type);

            if (FunctionalityTypesHelper::Subtask->value === $type?->getName()) {
                $functionality->setParentFunctionality(null);
            }

            $functionality->setDueDate($data->getDueDate());
            $functionality->setUpdatedAt(new \DateTime('Now'));
            $this->entityManager->persist($functionality);

            try {
                $this->entityManager->flush();

                $configOutput = new AutoMapperConfig();
                $configOutput->registerMapping(
                    Functionality::class,
                    FunctionalityOutputDto::class)
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
                    })->forMember('type', function (Functionality $functionality): FunctionalityCharacteristicOutputDto {
                        $typeConfig = new AutoMapperConfig();
                        $typeConfig->registerMapping(
                            FunctionalityType::class,
                            FunctionalityCharacteristicOutputDto::class
                        );
                        return (new AutoMapper($typeConfig))
                            ->map($functionality->getType(), FunctionalityCharacteristicOutputDto::class);
                    })->forMember('status', function (Functionality $functionality): FunctionalityCharacteristicOutputDto {
                        $statusConfig = new AutoMapperConfig();
                        $statusConfig->registerMapping(
                            FunctionalityStatus::class,
                            FunctionalityCharacteristicOutputDto::class
                        );
                        return (new AutoMapper($statusConfig))
                            ->map($functionality->getFunctionalityStatus(), FunctionalityCharacteristicOutputDto::class);
                    })->forMember('projectId', function (Functionality $functionality): int {
                        return $functionality->getProject()?->getId();
                    });
                $mapper = new AutoMapper($configOutput);
                /**
                 * @var FunctionalityOutputDto $functionalityDto
                 */
                $functionalityDto = $mapper->map($functionality, FunctionalityOutputDto::class);
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }

        return $functionalityDto ?? null;
    }
}
