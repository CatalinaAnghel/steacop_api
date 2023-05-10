<?php

namespace App\Repository;

use App\Entity\FunctionalityAttachment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FunctionalityAttachment>
 *
 * @method FunctionalityAttachment|null find($id, $lockMode = null, $lockVersion = null)
 * @method FunctionalityAttachment|null findOneBy(array $criteria, array $orderBy = null)
 * @method FunctionalityAttachment[]    findAll()
 * @method FunctionalityAttachment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FunctionalityAttachmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FunctionalityAttachment::class);
    }

    public function save(FunctionalityAttachment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FunctionalityAttachment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FunctionalityAttachment[] Returns an array of FunctionalityAttachment objects
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

//    public function findOneBySomeField($value): ?FunctionalityAttachment
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
