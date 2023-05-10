<?php
declare(strict_types=1);

namespace App\State\Processor\Functionality;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Assignment\Input\CreateAssignmentInputDto;
use App\Dto\Functionality\Input\CreateFunctionalityInputDto;
use App\Dto\Functionality\Output\FunctionalityOutputDto;
use App\Entity\Functionality;
use App\Entity\Project;
use App\Message\Command\Functionality\CreateFunctionalityCommand;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateFunctionalityProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $commandBus
    ) {}

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
            try {
                $config = new AutoMapperConfig();
                $config->registerMapping(CreateAssignmentInputDto::class,
                    Functionality::class);
                $mapper = new AutoMapper($config);
                /**
                 * @var Functionality $functionality
                 */
                $functionality = $mapper->map($data, Functionality::class);

                $this->commandBus->dispatch(new CreateFunctionalityCommand($functionality, $project));

                $configOutput = new AutoMapperConfig();
                $configOutput->registerMapping(
                    Functionality::class,
                    FunctionalityOutputDto::class
                )->forMember('documents', function (): array {
                    return [];
                });
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
