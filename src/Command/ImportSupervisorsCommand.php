<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Contracts\AbstractUsersCommand;
use App\Entity\Department;
use App\Entity\Supervisor;
use App\Entity\SupervisorImportFile;
use App\Entity\SupervisoryPlan;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
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
class ImportSupervisorsCommand extends AbstractUsersCommand {
    public const IMPORT_FILE_PATH = '\\..\\..\\..\\public\\documents\\supervisors\\';
    protected const DETAILED_DESCRIPTION = 'This command allows you to import the supervisors';

    /**
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher,
                                private readonly EntityManagerInterface      $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        $this->setHelp(self::DETAILED_DESCRIPTION)
            ->addArgument('fileName', InputArgument::OPTIONAL, 'Provide the file name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        if ($input->getArgument('fileName') === null) {
            $supervisorImportFileRepo = $this->entityManager->getRepository(SupervisorImportFile::class);
            try {
                $fileInfo = $supervisorImportFileRepo->findMostRecentFile();
            } catch (\Exception $exception) {
                dd($exception->getMessage());
            }

            if ($fileInfo !== null) {
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
                    $user->setPassword($this->userPasswordHasher->hashPassword($user, explode('@', $data['Email'])[0]));
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
                }
            }
            return Command::SUCCESS;
        }
        return Command::FAILURE;
    }

//    public function mapCSV(InputInterface $input): array {
//        $supervisors = [];
//        $supervisorDataType = [];
//        $rowCounter = 0;
//        $fileName = ($input->getArgument('fileName')?? 'professors') . '.csv';
//        $handle = fopen($this->getImportFilePath() . $fileName, 'r');
//        while (false !== ($data = fgetcsv($handle))) {
//            if (0 === $rowCounter) {
//                $supervisorDataType = $data;
//            } else {
//                $temp = [];
//                foreach ($data as $key => $value) {
//                    $temp[$supervisorDataType[$key]] = $value;
//                }
//                $supervisors[] = $temp;
//            }
//            $rowCounter++;
//        }
//        return $supervisors;
//    }
}