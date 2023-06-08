<?php

namespace App\Repository;

use App\Entity\Assignment;
use App\Entity\Project;
use App\Repository\Traits\GradeProviderTrait;
use App\Repository\Traits\LoggerTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Assignment>
 *
 * @method Assignment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Assignment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Assignment[]    findAll()
 * @method Assignment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssignmentRepository extends ServiceEntityRepository
{
    public const ALIAS = 'assignmentAlias';

    use GradeProviderTrait;
    use LoggerTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Assignment::class);
    }

    public function add(Assignment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Assignment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Count the assignments that have a due date in the past
     * @param int $projectId
     * @return int
     */
    public function countPassedAssignments(int $projectId): int
    {
        $qb = $this->createQueryBuilder(self::ALIAS);
        $qb->select('count(' . self::ALIAS . '.id) as numberOfPassedAssignments')
            ->innerJoin(
                Project::class,
                'project',
                Join::WITH,
                'project.id = ' . self::ALIAS . '.project'
            )
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq(
                        'project.id',
                        ':projectId'
                    ),
                    $qb->expr()->lte(
                        self::ALIAS . '.dueDate',
                        ':currentDate'
                    )
                )
            )
            ->setParameter('projectId', $projectId)
            ->setParameter('currentDate', new \DateTime('Now'));
        try {
            $result = $qb->getQuery()->getSingleResult();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return isset($result['numberOfPassedAssignments']) && $result['numberOfPassedAssignments'] > 0 ?
            $result['numberOfPassedAssignments'] : 1;
    }

    //    /**
    //     * @return Assignment[] Returns an array of Assignment objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Assignment
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
