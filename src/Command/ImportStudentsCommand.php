<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Contracts\AbstractUsersCommand;
use App\Command\Contracts\PasswordGeneratorTrait;
use App\Entity\Project;
use App\Entity\Specialization;
use App\Entity\Student;
use App\Entity\StudentImportFile;
use App\Entity\Supervisor;
use App\Entity\SupervisoryPlan;
use App\Entity\User;
use App\Service\CodeGeneratorService;
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
    name: 'app:import-students',
    description: 'Imports the students',
    aliases: ['app:add-students'],
    hidden: false
)]
class ImportStudentsCommand extends AbstractUsersCommand
{
    use PasswordGeneratorTrait;
    public const IMPORT_FILE_PATH = '\\..\\..\\..\\public\\documents\\students\\';
    protected const DETAILED_DESCRIPTION = 'This command allows you to import the students';

    /**
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher,
                                private readonly EntityManagerInterface      $entityManager,
                                private readonly LoggerInterface             $logger,
                                private readonly CodeGeneratorService        $codeGenerator
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp(self::DETAILED_DESCRIPTION)
            ->addArgument('fileName', InputArgument::OPTIONAL, 'Provide the file name');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $progressBar = new ProgressBar($output);
        $progressBar->start();
        if ($input->getArgument('fileName') === null) {
            $studentImportFileRepo = $this->entityManager->getRepository(StudentImportFile::class);
            try {
                $fileInfo = $studentImportFileRepo->findMostRecentFile();
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
            try {
                $this->setFileName($fileName);
                $userRepo = $this->entityManager->getRepository(User::class);
                $studentsData = $this->mapCSV();

                foreach ($studentsData as $data) {
                    if (isset($data['Email'], $data['Code'], $data['SupervisorCode']) &&
                        null === $userRepo->findOneBy(['code' => $data['Code']])) {
                        $specialization = null;
                        $supervisoryPlan = null;
                        if (isset($data['Specialization'])) {
                            $specializationRepo = $this->entityManager->getRepository(Specialization::class);
                            $specialization = $specializationRepo->findOneBy(['name' => $data['Specialization']]);
                        }

                        if (isset($data['SupervisoryPlan'])) {
                            $supervisoryStyleRepo = $this->entityManager->getRepository(SupervisoryPlan::class);
                            $supervisoryPlan = $supervisoryStyleRepo->findOneBy(['name' => $data['SupervisoryPlan']]);
                        }

                        $project = $this->insertProject($data);
                        $user = $this->insertUser($data);
                        if (null !== $supervisoryPlan && null !== $specialization &&
                            null !== $project && null !== $user) {
                            $student = new Student();
                            $student->setUser($user);
                            $student->setSupervisoryPlan($supervisoryPlan);
                            $student->setProject($project);
                            $student->setSpecialization($specialization);
                            $student->setFirstName($data['FirstName'] ?? '');
                            $student->setLastName($data['LastName'] ?? '');
                            $student->setPhoneNumber($data['PhoneNumber'] ?? '');
                            $this->entityManager->persist($student);
                            $this->entityManager->flush();
                            $progressBar->advance();
                        }
                    }
                }
            }catch (\Exception $exception){
                $this->logger->error($exception->getMessage());
                return Command::FAILURE;
            }
        }
        $progressBar->finish();
        return Command::SUCCESS;
    }

    /**
     * @param array $data
     * @return User|null
     */
    private function insertUser(array $data): User|null
    {
        try {
            $user = new User();
            $user->setEmail($data['Email']);
            $user->setCode($data['Code']);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $this->createPassword($data['Email'], $data['Code'])));
            $user->setRoles(['ROLE_STUDENT']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return $user ?? null;
    }

    /**
     * @param array $data
     * @return Project|null
     */
    private function insertProject(array $data): Project|null
    {
        $supervisorRepo = $this->entityManager->getRepository(Supervisor::class);
        $supervisor = $supervisorRepo->findByCode((string)$data['SupervisorCode']);
        if (null !== $supervisor) {
            try {
                $code = $this->codeGenerator->generateUniqueCode($data['LastName'], $supervisor->getLastName());
                $project = new Project();
                $project->setDescription($data['ProjectDescription'] ?? '');
                $project->setTitle($data['ProjectTitle'] ?? '');
                $project->setSupervisor($supervisor);
                $project->setCode($code);
                $this->entityManager->persist($project);
                $this->entityManager->flush();
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }
        return $project ?? null;
    }
}
