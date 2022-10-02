<?php

namespace App\Repository;

use App\Entity\GuidanceMeeting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GuidanceMeeting>
 *
 * @method GuidanceMeeting|null find($id, $lockMode = null, $lockVersion = null)
 * @method GuidanceMeeting|null findOneBy(array $criteria, array $orderBy = null)
 * @method GuidanceMeeting[]    findAll()
 * @method GuidanceMeeting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuidanceMeetingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuidanceMeeting::class);
    }

    public function add(GuidanceMeeting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(GuidanceMeeting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return GuidanceMeeting[] Returns an array of GuidanceMeeting objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GuidanceMeeting
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
