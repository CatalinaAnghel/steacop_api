<?php

namespace App\Repository;

use App\Entity\Functionality;
use App\Entity\FunctionalityStatus;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Functionality>
 *
 * @method Functionality|null find($id, $lockMode = null, $lockVersion = null)
 * @method Functionality|null findOneBy(array $criteria, array $orderBy = null)
 * @method Functionality[]    findAll()
 * @method Functionality[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FunctionalityRepository extends ServiceEntityRepository
{
    public const ALIAS = 'functionalities_alias';

    public function __construct(ManagerRegistry $registry, private readonly LoggerInterface $logger)
    {
        parent::__construct($registry, Functionality::class);
    }

    public function add(Functionality $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Functionality $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getNextAvailableCode(int $projectId): int
    {
        $qb = $this->createQueryBuilder('f');
        $qb->select('MAX(f.code) as nextCode')
            ->innerJoin(
                Project::class,
                'project',
                Join::WITH,
                'project.id = f.project'
            )
            ->where('project.id = :projectId')
            ->setParameter('projectId', $projectId);
        try {
            $result = $qb->getQuery()->getSingleResult();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return isset($result['nextCode']) ? (int)$result['nextCode'] + 1 : 1;
    }

    /**
     * Returns the next available order number based on the project and the desired status
     * @param int $projectId
     * @param int $statusId
     * @return int
     */
    public function getNextOrderNumber(int $projectId, int $statusId): int
    {
        $qb = $this->createQueryBuilder(self::ALIAS);
        $qb->select('MAX('.self::ALIAS.'.orderNumber) as nextOrderNumber')
            ->innerJoin(
                Project::class,
                'project',
                Join::WITH,
                'project.id = '.self::ALIAS.'.project'
            )
            ->innerJoin(
                FunctionalityStatus::class,
                'status',
                Join::WITH,
                'status.id = ' . self::ALIAS . '.functionalityStatus'
            )
            ->where('project.id = :projectId')
            ->andWhere('status.id = :statusId')
            ->andWhere($qb->expr()->isNotNull(self::ALIAS . '.orderNumber'))
            ->setParameter('projectId', $projectId)
            ->setParameter('statusId', $statusId);
        try {
            $result = $qb->getQuery()->getSingleResult();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return isset($result['nextOrderNumber']) ? (int)$result['nextOrderNumber'] + 1 : 1;
    }

    /**
     * Update the order number of the next functionalities based on the type
     * @param int $startOrderNumber
     * @param int $positionOffset
     * @param int $projectId
     * @param int $statusId
     * @return void
     */
    public function updateOrder(int $startOrderNumber, int $positionOffset, int $projectId, int $statusId): void
    {
        $qb = $this->createQueryBuilder(self::ALIAS);
        $query = $qb->update()
            ->set(self::ALIAS . '.orderNumber', self::ALIAS . '.orderNumber + :offset')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->isNotNull(self::ALIAS . '.orderNumber'),
                    $qb->expr()->eq(self::ALIAS . '.project', ':projectId'),
                    $qb->expr()->gt(self::ALIAS . '.orderNumber', ':startingOrderNumber'),
                    $qb->expr()->eq(self::ALIAS . '.functionalityStatus', ':statusId')
                )
            )
            ->setParameter('offset', $positionOffset)
            ->setParameter('projectId', $projectId)
            ->setParameter('startingOrderNumber', $startOrderNumber)
            ->setParameter('statusId', $statusId)
            ->getQuery();

        try {
            $query->execute();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    //    /**
    //     * @return Functionality[] Returns an array of Functionality objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Functionality
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
