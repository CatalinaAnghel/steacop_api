<?php
declare(strict_types=1);

namespace App\State\Provider\Contracts;

use ApiPlatform\State\ProviderInterface;
use App\Dto\CustomSupervisoryPlan\CustomSupervisoryPlanDto;
use App\Entity\Project;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractPlanProvider implements ProviderInterface {
    public function __construct(private readonly EntityManagerInterface $entityManager) {
    }

    protected function determineSupervisoryPlan(Project $project): CustomSupervisoryPlanDto|null{
        $studentRepo = $this->entityManager->getRepository(Student::class);
        $student = $studentRepo->findOneBy(['project' => $project->getId()]);
        $supervisor = $project->getSupervisor();
        $studentSupervisoryPlan = $student?->getSupervisoryPlan();
        $supervisorPlan = $supervisor?->getSupervisoryPlan();

        if (null === $supervisorPlan || null === $studentSupervisoryPlan) {
            return null;
        }
        $planDto = new CustomSupervisoryPlanDto();
        if ($studentSupervisoryPlan->getId() !== $supervisorPlan->getId()) {
            $meanAssignmentsNumber = ($studentSupervisoryPlan->getNumberOfAssignments() +
                    $supervisorPlan->getNumberOfAssignments()) / 2;
            $meanMeetingsNumber = ($studentSupervisoryPlan->getNumberOfGuidanceMeetings() +
                    $supervisorPlan->getNumberOfGuidanceMeetings()) / 2;
            $planDto->setNumberOfAssignments((int)$meanAssignmentsNumber);
            $planDto->setNumberOfGuidanceMeetings((int)$meanMeetingsNumber);
        } else {
            $planDto->setNumberOfGuidanceMeetings($studentSupervisoryPlan->getNumberOfGuidanceMeetings());
            $planDto->setNumberOfAssignments($studentSupervisoryPlan->getNumberOfAssignments());
        }

        return $planDto;
    }
}
