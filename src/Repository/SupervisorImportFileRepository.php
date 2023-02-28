<?php

namespace App\Repository;

use App\Entity\SupervisorImportFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SupervisorImportFile>
 *
 * @method SupervisorImportFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupervisorImportFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupervisorImportFile[]    findAll()
 * @method SupervisorImportFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupervisorImportFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupervisorImportFile::class);
    }

    public function save(SupervisorImportFile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SupervisorImportFile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return SupervisorImportFile|null Returns the details of the latest file that has been uploaded
     * @throws NonUniqueResultException
     */
    public function findMostRecentFile(): SupervisorImportFile|null {
        return $this->createQueryBuilder('s')
            ->orderBy('s.createdAt', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return SupervisorImportFile[] Returns an array of SupervisorImportFile objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SupervisorImportFile
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
