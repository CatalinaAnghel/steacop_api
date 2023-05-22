<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\FunctionalityStatus;
use App\Entity\FunctionalityType;
use App\Helper\FunctionalityStatusesHelper;
use App\Helper\FunctionalityTypesHelper;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:prepare-functionalities-data',
    description: 'Inserts the data needed for the functionalities',
    aliases: ['app:add-functionalities-data'],
    hidden: false
)]
class ImportFunctionalityTypesAndStatusCommand extends Command
{
    public const DETAILED_DESCRIPTION = 'Inserts the data needed for the functionalities';
    /**
     * @var FunctionalityTypesHelper[]
     */
    private array $types;

    /**
     * @var FunctionalityTypesHelper[]
     */
    private array $statuses;

    /**
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $logger
    )
    {
        parent::__construct();
        $this->types = [
            FunctionalityTypesHelper::Epic,
            FunctionalityTypesHelper::Story,
            FunctionalityTypesHelper::Task,
            FunctionalityTypesHelper::Subtask,
            FunctionalityTypesHelper::Bug
        ];

        $this->statuses = FunctionalityStatusesHelper::getOrder();
    }

    protected function configure(): void
    {
        $this->setHelp(self::DETAILED_DESCRIPTION);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $progressBar = new ProgressBar($output, count($this->types) + count($this->statuses));
        $progressBar->start();
        $progressBar->setMessage('Preparing...');
        try {

            $progressBar->setMessage('Creating the functionality types and persisting them...');
            $entities = [
                [
                    'entity' => FunctionalityType::class,
                    'propName' => 'types'
                ],
                [
                    'entity' => FunctionalityStatus::class,
                    'propName' => 'statuses'
                ]
            ];
            foreach ($entities as $entity){
                $propName = $entity['propName'];
                $this->prepare($entity['entity']);
                foreach ($this->$propName as $key => $value) {
                    $typeObject = new $entity['entity']();
                    $typeObject->setName($value->value);
                    $reflectionClass = new \ReflectionClass($entity['entity']);
                    if ($reflectionClass->hasProperty('orderNumber')) {
                        $typeObject->setOrderNumber($key + 1);
                    }
                    $progressBar->advance();
                    $this->entityManager->persist($typeObject);
                }

                $progressBar->setMessage('Inserting the data for ' . $entity['entity']);
                $this->entityManager->flush();
                $progressBar->setMessage('Creating the ' . $entity['entity'] . ' and persisting them...');
            }
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            return Command::FAILURE;
        }
        $progressBar->finish();

        return Command::SUCCESS;
    }

    /**
     * @throws Exception
     */
    private function prepare($entity): void
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->entityManager->getConnection()->executeStatement('SET FOREIGN_KEY_CHECKS=0');
            $cmd = $this->entityManager->getClassMetadata($entity);
            $this->entityManager->getConnection()->executeStatement('DELETE FROM ' . $cmd->getTableName());
            $this->entityManager->getConnection()->executeStatement('SET FOREIGN_KEY_CHECKS=1');
            $this->entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->entityManager->getConnection()->rollBack();
        }
    }
}
