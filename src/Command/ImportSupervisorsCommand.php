<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Contracts\AbstractUsersCommand;
use App\Command\Contracts\PasswordGeneratorTrait;
use App\Entity\Department;
use App\Entity\Supervisor;
use App\Entity\SupervisorImportFile;
use App\Entity\SupervisoryPlan;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:import-supervisors',
    description: 'Imports the supervisors',
    aliases: ['app:add-supervisors'],
    hidden: false
)]
class ImportSupervisorsCommand extends AbstractUsersCommand
{
    use PasswordGeneratorTrait;
    public const IMPORT_FILE_PATH = '\\..\\..\\..\\public\\documents\\supervisors\\';
    protected const DETAILED_DESCRIPTION = 'This command allows you to import the supervisors';

    /**
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher,
                                private readonly EntityManagerInterface      $entityManager,
                                private readonly LoggerInterface             $logger
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp(self::DETAILED_DESCRIPTION)
            ->addArgument('fileName', InputArgument::OPTIONAL, 'Provide the file name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $progressBar = new ProgressBar($output);
        $progressBar->start();
        if ($input->getArgument('fileName') === null) {
            $supervisorImportFileRepo = $this->entityManager->getRepository(SupervisorImportFile::class);
            try {
                $fileInfo = $supervisorImportFileRepo->findMostRecentFile();
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
                return Command::FAILURE;
            }

            if (isset($fileInfo)) {
                $fileName = $fileInfo->getFilePath();
            }
        } else {
            $fileName = $input->getArgument('fileName');
        }
        if (isset($fileName)) {
            $this->setFileName($fileName);
            $userRepo = $this->entityManager->getRepository(User::class);
            $supervisorsData = $this->mapCSV();
            foreach ($supervisorsData as $data) {
                if (null === $userRepo->findOneBy(['code' => $data['Code']])) {
                    $departmentRepo = $this->entityManager->getRepository(Department::class);
                    $department = $departmentRepo->findOneBy(['name' => $data['Department']]);

                    $supervisoryStyleRepo = $this->entityManager->getRepository(SupervisoryPlan::class);
                    $supervisoryPlan = $supervisoryStyleRepo->findOneBy(['name' => $data['SupervisoryPlan']]);

                    $user = new User();
                    $user->setEmail($data['Email']);
                    $user->setCode($data['Code']);
                    $user->setPassword($this->userPasswordHasher->hashPassword($user, $this->createPassword($data['Email'], $data['Code'])));
                    $user->setRoles(['ROLE_TEACHER']);
                    $this->entityManager->persist($user);
                    $this->entityManager->flush();

                    $supervisor = new Supervisor();
                    $supervisor->setLastName($data['LastName']);
                    $supervisor->setFirstName($data['FirstName']);
                    $supervisor->setPhoneNumber($data['PhoneNumber']);
                    $supervisor->setSupervisoryPlan($supervisoryPlan);
                    $supervisor->setDepartment($department);
                    $supervisor->setUser($user);
                    $this->entityManager->persist($supervisor);
                    $this->entityManager->flush();
                    $progressBar->advance();
                }
            }
            return Command::SUCCESS;
        }
        $progressBar->finish();
        return Command::FAILURE;
    }
}
