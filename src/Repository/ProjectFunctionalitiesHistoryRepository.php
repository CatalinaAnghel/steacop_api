<?php

namespace App\Repository;

use App\Entity\ProjectFunctionalitiesHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectFunctionalitiesHistory>
 *
 * @method ProjectFunctionalitiesHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectFunctionalitiesHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectFunctionalitiesHistory[]    findAll()
 * @method ProjectFunctionalitiesHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectFunctionalitiesHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectFunctionalitiesHistory::class);
    }

    public function save(ProjectFunctionalitiesHistory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProjectFunctionalitiesHistory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ProjectFunctionalitiesHistory[] Returns an array of ProjectFunctionalitiesHistory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProjectFunctionalitiesHistory
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
