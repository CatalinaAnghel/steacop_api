<?php
declare(strict_types=1);

namespace App\State\Provider\Project;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\ProjectCollaborationScore;
use App\ApiResource\StudentData;
use App\Entity\Project;
use App\Entity\ScoreWeight;
use App\Helper\WeightsHelper;
use App\Service\ScoreCalculationStrategy\RatingScoreStrategy;
use App\Service\ScoreCalculationStrategy\StructureScoreStrategy;
use App\Service\ScoreCalculationStrategy\SupportScoreStrategy;
use App\Service\ScoreCalculatorService;
use Doctrine\ORM\EntityManagerInterface;

class GetProjectCollaborationScoreProvider implements ProviderInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $collaborationScore = null;
        $projectRepo = $this->entityManager->getRepository(Project::class);
        $project = $projectRepo->findOneBy(['id' => (int) $uriVariables['projectId']]);
        if (null !== $project) {
            $context = new ScoreCalculatorService(new RatingScoreStrategy($this->entityManager));
            $ratingScore = $context->computeScore($project);

            $context->setStrategy(new SupportScoreStrategy($this->entityManager));
            $supportScore = $context->computeScore($project);
            
            $context->setStrategy(new StructureScoreStrategy($this->entityManager));
            $structureScore = $context->computeScore($project);

            $studentData = new StudentData();
            $studentData->setFirstName($project->getStudent()?->getFirstName());
            $studentData->setLastName($project->getStudent()?->getLastName());

            $collaborationScore = new ProjectCollaborationScore();
            $collaborationScore->setProjectId((int) $uriVariables['projectId'])
                ->setStudentData($studentData)
                ->setStructureScore($structureScore)
                ->setRatingScore($ratingScore)
                ->setSupportScore($supportScore);
            $this->computeTotalScore($collaborationScore);
        }

        return $collaborationScore;
    }

    /**
     * Computes the total score
     * @param ProjectCollaborationScore $projectCollaborationScore
     * @return void
     */
    private function computeTotalScore(ProjectCollaborationScore $projectCollaborationScore): void
    {
        $defaultWeight = 1.0 / 3;
        $weightRepository = $this->entityManager->getRepository(ScoreWeight::class);
        $ratingScoreWeightObj = $weightRepository->findOneBy([
            'name' => WeightsHelper::RatingWeight->getWeightName()
        ]);
        $supportScoreWeightObj = $weightRepository->findOneBy([
            'name' => WeightsHelper::SupportWeight->getWeightName()
        ]);
        $structureScoreWeightObj = $weightRepository->findOneBy([
            'name' => WeightsHelper::StructureWeight->getWeightName()
        ]);

        $ratingScoreWeight = (float)(null !== $ratingScoreWeightObj ? $ratingScoreWeightObj->getWeight() / 100.0 :
            $defaultWeight);
        $supportScoreWeight = (float)(null !== $supportScoreWeightObj ? $supportScoreWeightObj->getWeight() / 100.0 :
            $defaultWeight);
        $structureScoreWeight = (float)(null !== $structureScoreWeightObj ? $structureScoreWeightObj->getWeight() / 100.0 :
            $defaultWeight);

        $totalScore = (float)$projectCollaborationScore->getRatingScore()->getTotalScore() * $ratingScoreWeight +
            (float)$projectCollaborationScore->getSupportScore()->getTotalScore() * $supportScoreWeight +
            (float)$projectCollaborationScore->getStructureScore()->getTotalScore() * $structureScoreWeight;

        $projectCollaborationScore->setScore($totalScore);
    }
}