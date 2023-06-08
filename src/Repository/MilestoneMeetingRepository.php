<?php

namespace App\Repository;

use App\Entity\MilestoneMeeting;
use App\Entity\Project;
use App\Repository\Traits\GradeProviderTrait;
use App\Repository\Traits\LoggerTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<MilestoneMeeting>
 *
 * @method MilestoneMeeting|null find($id, $lockMode = null, $lockVersion = null)
 * @method MilestoneMeeting|null findOneBy(array $criteria, array $orderBy = null)
 * @method MilestoneMeeting[]    findAll()
 * @method MilestoneMeeting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MilestoneMeetingRepository extends ServiceEntityRepository
{
    public const ALIAS = 'milestone_meeting';

    use GradeProviderTrait;
    use LoggerTrait;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, MilestoneMeeting::class);
        $this->logger = $logger;
    }

    public function add(MilestoneMeeting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MilestoneMeeting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return MilestoneMeeting[] Returns an array of MilestoneMeeting objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MilestoneMeeting
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
