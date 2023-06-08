<?php
declare(strict_types=1);

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Grades;
use App\ApiResource\StudentData;
use App\ApiResource\StudentGrades;
use App\Entity\Assignment;
use App\Entity\MilestoneMeeting;
use App\Entity\Student;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class GetStudentGradesProvider implements ProviderInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security               $security
    ) {}


    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();
        $userObj = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $user?->getUserIdentifier()]);
        $students = $this->entityManager->getRepository(Student::class)
            ->findSupervisedStudents($user?->getUserIdentifier());

        $studentGrades = new StudentGrades();
        $grades = [];
        foreach ($students as $student) {
            $studentGrades->setSupervisorCode($userObj->getCode());
            $studentData = (new StudentData())
                ->setLastName($student->getLastName())
                ->setFirstName($student->getFirstName());

            $assignmentGrade = $this->computeGrade(
                $this->entityManager->getRepository(Assignment::class)
                    ->getGrades($student->getProject()?->getId()));

            $milestoneGrade = $this->computeGrade(
                $this->entityManager->getRepository(MilestoneMeeting::class)
                    ->getGrades($student->getProject()?->getId()));


            $totalGrade = $student->getProject()->getGrade();
            $grades[] = (new Grades())
                ->setStudentData($studentData)
                ->setAssignmentsGrade($assignmentGrade)
                ->setMilestoneMeetingsGrade($milestoneGrade)
                ->setTotalGrade($totalGrade?? 0);
        }
        $studentGrades->setGradesCollection($grades);

        return $studentGrades;
    }

    /**
     * @param array $grades
     * @return float
     */
    private function computeGrade(array $grades): float
    {
        $sum = 0.0;
        foreach ($grades as $grade) {
            $sum += $grade['grade'];
        }

        return !empty($grades) ? $sum / count($grades) : 0;
    }
}
