<?php
declare(strict_types=1);

namespace App\State\Processor\Functionality;

use ApiPlatform\Metadata\Operation;
use App\Dto\Functionality\Input\PatchFunctionalityInputDto;
use App\Dto\Functionality\Output\FunctionalityOutputDto;
use App\Dto\FunctionalityAttachment\Output\FunctionalityAttachmentOutputDto;
use App\Entity\Functionality;
use App\Entity\FunctionalityAttachment;
use App\Entity\FunctionalityStatus;
use App\Entity\FunctionalityStatusHistory;
use App\Entity\FunctionalityType;
use App\Helper\FunctionalityTypesHelper;
use App\State\Processor\Contracts\AbstractFunctionalityProcessor;
use App\Validator\Contracts\ValidatorInterface;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PatchFunctionalityStateProcessor extends AbstractFunctionalityProcessor
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $logger,
        private readonly ValidatorInterface     $functionalityValidator
    )
    {
        date_default_timezone_set('Europe/Bucharest');
        parent::__construct($this->entityManager);
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
            $oldStatus = $functionality->getFunctionalityStatus();
            if (null !== $status && $functionality->getFunctionalityStatus()?->getId() !== $status->getId()) {
                // log the update of the status of the functionality
                $functionalityLog = new FunctionalityStatusHistory();
                $functionalityLog->setCreatedAt(new \DateTimeImmutable('Now'))
                    ->setFunctionality($functionality)
                    ->setOldStatus($oldStatus)
                    ->setNewStatus($status);
                $this->entityManager->persist($functionalityLog);

                // update the functionality
                $functionality->setFunctionalityStatus($status);
                $functionality->setOrderNumber($functionalityRepo->getNextOrderNumber(
                    $functionality->getProject()?->getId(),
                    $status->getId()
                ));
            }

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

                if(null !== $oldStatus){
                    $historyDate = new \DateTime('Now');
                    $oldStatusHistory = $this->createHistory(
                        $oldStatus,
                        $functionality->getProject(),
                        $historyDate
                    );
                    $this->entityManager->persist($oldStatusHistory);

                    $newStatusHistory = $this->createHistory(
                        $status,
                        $functionality->getProject(),
                        $historyDate
                    );
                    $this->entityManager->persist($newStatusHistory);
                    $this->entityManager->flush();
                }

                $configOutput = new AutoMapperConfig();
                $this->addCommonOutputMapping(
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
                    }));
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
