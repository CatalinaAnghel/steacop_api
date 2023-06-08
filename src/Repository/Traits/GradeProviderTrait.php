<?php
declare(strict_types=1);

namespace App\Repository\Traits;

use App\Entity\Project;
use Doctrine\ORM\Query\Expr\Join;

trait GradeProviderTrait
{
    /**
     * @param int $projectId
     * @return array
     */
    public function getGrades(int $projectId): array{
        $qb = $this->createQueryBuilder(self::ALIAS);
        $qb->select(self::ALIAS . '.grade')
            ->innerJoin(
                Project::class,
                'projects',
                Join::WITH,
                'projects.id = ' . self::ALIAS . '.project'
            )
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->isNotNull(
                        self::ALIAS . '.grade'
                    ),
                    $qb->expr()->eq(self::ALIAS . '.project', ':projectId')
                )
            )
            ->setParameter('projectId', $projectId);
        try {
            $results = $qb->getQuery()->getResult();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return $results?? [];
    }
}
