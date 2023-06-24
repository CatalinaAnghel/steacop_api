<?php
declare(strict_types=1);

namespace App\Service\ScoreCalculationStrategy;

use App\ApiResource\BaseScore;
use App\Entity\Project;
use App\Entity\Rating;
use App\Entity\ScoreWeight;
use App\Helper\WeightsHelper;
use App\Service\Contract\CalculationStrategyInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class RatingScoreStrategy implements CalculationStrategyInterface
{
    public const MAX_RATING = 5;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function computeScore(Project $project): BaseScore
    {
        $totalScore = 0.0;
        $studentRatings = $project->getStudent()?->getUser()?->getRatings();
        $supervisorRatings = $project->getSupervisor()?->getUser()?->getRatings()
            ->filter(function (Rating $rating) use ($project) {
                return $rating->getMeeting()->getProject()->getId() === $project->getId();
            });

        $studentRatingScore = $this->computePartialRatingScore($studentRatings);
        $supervisorRatingScore = $this->computePartialRatingScore($supervisorRatings);
        $supervisorWeight = $this->entityManager->getRepository(ScoreWeight::class)
            ->findOneBy(['name' => WeightsHelper::SupervisorRatingWeight->name]);
        $weight = null !== $supervisorWeight ? (float) $supervisorWeight->getWeight() : 50.0;

        $totalScore = $supervisorRatingScore * $weight / 100 +
            $studentRatingScore * (100 - $weight) / 100;

        return (new BaseScore())->setTotalScore($totalScore);
    }

    /**
     * @param Collection<Rating> $ratings
     * @return float
     */
    private function computePartialRatingScore(Collection $ratings): float
    {
        $score = 0.0;
        $numberOfRatings = $ratings->count();
        if ($numberOfRatings > 0) {
            $ratingValue = 0.0;
            foreach ($ratings as $rating) {
                $ratingValue += $rating->getValue() / self::MAX_RATING * 10;

            }
            $score = $ratingValue / $numberOfRatings * 10;
        }

        return $score;
    }
}