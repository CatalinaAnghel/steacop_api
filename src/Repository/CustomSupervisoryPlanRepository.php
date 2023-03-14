<?php

namespace App\Repository;

use App\Entity\CustomSupervisoryPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomSupervisoryPlan>
 *
 * @method CustomSupervisoryPlan|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomSupervisoryPlan|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomSupervisoryPlan[]    findAll()
 * @method CustomSupervisoryPlan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomSupervisoryPlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomSupervisoryPlan::class);
    }

    public function save(CustomSupervisoryPlan $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CustomSupervisoryPlan $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CustomSupervisoryPlan[] Returns an array of CustomSupervisoryPlan objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CustomSupervisoryPlan
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
