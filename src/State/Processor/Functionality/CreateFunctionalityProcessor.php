<?php
declare(strict_types=1);

namespace App\State\Processor\Functionality;

use ApiPlatform\Metadata\Operation;
use App\Dto\Functionality\Input\CreateFunctionalityInputDto;
use App\Dto\Functionality\Output\FunctionalityOutputDto;
use App\Entity\Functionality;
use App\Entity\FunctionalityStatus;
use App\Entity\FunctionalityType;
use App\Entity\Project;
use App\Helper\FunctionalityStatusesHelper;
use App\Helper\FunctionalityTypesHelper;
use App\Message\Command\Functionality\CreateFunctionalityCommand;
use App\State\Processor\Contracts\AbstractFunctionalityProcessor;
use App\Validator\Contracts\ValidatorInterface;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateFunctionalityProcessor extends AbstractFunctionalityProcessor
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $logger,
        private readonly MessageBusInterface    $commandBus,
        private readonly ValidatorInterface     $functionalityValidator
    )
    {
        parent::__construct($this->entityManager);
        date_default_timezone_set('Europe/Bucharest');
    }

    /**
     * @inheritDoc
     * @param CreateFunctionalityInputDto $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []):
    ?FunctionalityOutputDto
    {
        $projectRepo = $this->entityManager->getRepository(Project::class);
        $project = $projectRepo->findOneBy(['id' => $data->getProjectId()]);

        if (null !== $project) {
            $this->functionalityValidator->validate($data);
            try {
                $config = new AutoMapperConfig();
                $config->registerMapping(
                    CreateFunctionalityInputDto::class,
                    Functionality::class
                )->forMember('type', function (CreateFunctionalityInputDto $dto): FunctionalityType {
                    return $this->entityManager->getRepository(FunctionalityType::class)
                        ->findOneBy(['id' => $dto->getType()]);
                })
                    ->forMember('functionalityStatus', function (): FunctionalityStatus {
                        return $this->entityManager->getRepository(FunctionalityStatus::class)
                            ->findOneBy(['name' => FunctionalityStatusesHelper::Open->value]);
                    })
                    ->forMember('code', function (CreateFunctionalityInputDto $dto): int {
                        return $this->entityManager->getRepository(Functionality::class)
                            ->getNextAvailableCode($dto->getProjectId());
                    })
                    ->forMember('parentFunctionality', function (CreateFunctionalityInputDto $dto): ?Functionality {
                        if (null !== $dto->getParentFunctionalityId()) {
                            $parentFunctionality = $this->entityManager->getRepository(Functionality::class)
                                ->findOneBy(['id' => $dto->getParentFunctionalityId()]);
                        }
                        return $parentFunctionality ?? null;
                    })
                    ->forMember('orderNumber', function (CreateFunctionalityInputDto $source): ?int {
                        $type = $this->entityManager->getRepository(FunctionalityType::class)
                            ->findOneBy(['id' => $source->getType()]);
                        if(null === $type || $type->getName() === FunctionalityTypesHelper::Epic->value){
                            return null;
                        }

                        return $this->entityManager->getRepository(Functionality::class)
                            ->getNextOrderNumber(
                                $source->getProjectId(),
                                $this->entityManager->getRepository(FunctionalityStatus::class)
                                    ->findOneBy(['name' => FunctionalityStatusesHelper::Open->value])?->getId()
                            );
                    });

                $mapper = new AutoMapper($config);

                /**
                 * @var Functionality $functionality
                 */
                $functionality = $mapper->map($data, Functionality::class);

                $this->commandBus->dispatch(new CreateFunctionalityCommand($functionality, $project));

                $configOutput = new AutoMapperConfig();
                $this->addCommonOutputMapping($configOutput->registerMapping(
                    Functionality::class,
                    FunctionalityOutputDto::class
                )->forMember('functionalityAttachments', function (): array {
                    return [];
                }));
                $mapper = new AutoMapper($configOutput);

                /**
                 * @var FunctionalityOutputDto $functionalityDto
                 */
                $functionalityDto = $mapper->map($functionality, FunctionalityOutputDto::class);

            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        } else {
            throw new NotFoundHttpException('The project could not be found');
        }

        return $functionalityDto ?? null;
    }
}
