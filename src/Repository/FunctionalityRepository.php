<?php

namespace App\Repository;

use App\Entity\Functionality;
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
        try{
            $result = $qb->getQuery()->getSingleResult();
        }catch (\Exception $exception){
            $this->logger->error($exception->getMessage());
        }

        return isset($result['nextCode']) ? (int)$result['nextCode']: 0;
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
