<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Contracts\AbstractUsersCommand;
use App\Entity\Project;
use App\Entity\Specialization;
use App\Entity\Student;
use App\Entity\Supervisor;
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
    name: 'app:import-students',
    description: 'Imports the students',
    aliases: ['app:add-students'],
    hidden: false
)]
class ImportStudentsCommand extends AbstractUsersCommand {
    public const IMPORT_FILE_PATH = '\\..\\..\\..\\public\\media\\students\\';
    protected const DETAILED_DESCRIPTION = 'This command allows you to import the students';

    /**
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher,
                                private readonly EntityManagerInterface      $entityManager) {
        parent::__construct();
    }

    protected function configure(): void {
        $this->setHelp(self::DETAILED_DESCRIPTION)
            ->addArgument('fileName', InputArgument::OPTIONAL, 'Provide the file name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $fileName = ($input->getArgument('fileName')?? 'students') . '.csv';
        $this->setFileName($fileName);
        $userRepo = $this->entityManager->getRepository(User::class);
        $studentsData = $this->mapCSV();

        foreach ($studentsData as $data) {
            if (isset($data['Email'], $data['Code'], $data['SupervisorCode']) &&
                null === $userRepo->findOneBy(['code' => $data['Code']])) {
                $specialization = null;
                $supervisoryPlan = null;
                if (isset($data['Specialization'])){
                    $specializationRepo = $this->entityManager->getRepository(Specialization::class);
                    $specialization = $specializationRepo->findOneBy(['name' => $data['Specialization']]);
                }

                if(isset($data['SupervisoryPlan'])){
                    $supervisoryStyleRepo = $this->entityManager->getRepository(SupervisoryPlan::class);
                    $supervisoryPlan = $supervisoryStyleRepo->findOneBy(['name' => $data['SupervisoryPlan']]);
                }

                $project = $this->insertProject($data);
                $user = $this->insertUser($data);
                if (null !== $supervisoryPlan && null !== $specialization && null !== $project && null !== $user){
                    $student = new Student();
                    $student->setUser($user);
                    $student->setSupervisoryPlan($supervisoryPlan);
                    $student->setProject($project);
                    $student->setSpecialization($specialization);
                    $student->setFirstName($data['FirstName']?? '');
                    $student->setLastName($data['LastName']?? '');
                    $student->setPhoneNumber($data['PhoneNumber']?? '');
                    $this->entityManager->persist($student);
                    $this->entityManager->flush();
                }
            }
        }
        return Command::SUCCESS;
    }

    /**
     * @param array $data
     * @return User|null
     */
    private function insertUser(array $data): User|null{
        try{
            $user = new User();
            $user->setEmail($data['Email']);
            $user->setCode($data['Code']);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, explode('@', $data['Email'])[0]));
            $user->setRoles(['ROLE_STUDENT']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }catch(\Exception $exception){
            dd($exception->getMessage());
        }

        return $user?? null;
    }

    /**
     * @param array $data
     * @return Project|null
     */
    private function insertProject(array $data): Project|null{
        $supervisorRepo = $this->entityManager->getRepository(Supervisor::class);
        $supervisor = $supervisorRepo->findByCode((string) $data['SupervisorCode']);
        if(null !== $supervisor){
            try{
                $project = new Project();
                $project->setDescription($data['ProjectDescription']?? '');
                $project->setTitle($data['ProjectTitle']?? '');
                $project->setSupervisor($supervisor);

                $this->entityManager->persist($project);
                $this->entityManager->flush();
            }catch (\Exception $exception){
                dd($exception->getMessage());
            }
        }
        return $project?? null;
    }
}