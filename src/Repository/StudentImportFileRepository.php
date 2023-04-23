<?php

namespace App\Repository;

use App\Entity\StudentImportFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StudentImportFile>
 *
 * @method StudentImportFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentImportFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentImportFile[]    findAll()
 * @method StudentImportFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentImportFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentImportFile::class);
    }

    public function save(StudentImportFile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(StudentImportFile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return StudentImportFile|null Returns the details of the latest file that has been uploaded
     * @throws NonUniqueResultException
     */
    public function findMostRecentFile(): StudentImportFile|null
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.createdAt', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    public function findOneBySomeField($value): ?StudentImportFile
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
