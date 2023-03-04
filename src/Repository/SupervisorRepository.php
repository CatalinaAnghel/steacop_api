<?php

namespace App\Repository;

use App\Entity\Supervisor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Supervisor>
 *
 * @method Supervisor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Supervisor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Supervisor[]    findAll()
 * @method Supervisor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupervisorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly LoggerInterface $logger)
    {
        parent::__construct($registry, Supervisor::class);
    }

    public function add(Supervisor $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Supervisor $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Selects the supervisor based on the code
     *
     * @param string $code
     * @return Supervisor|null
     */
    public function findByCode(string $code): Supervisor|null{
        try{
            $supervisor =  $this->createQueryBuilder('supervisor')
                ->innerJoin('supervisor.user', 'user')
                ->andWhere('user.code = :code')
                ->setParameter('code', $code)
                ->getQuery()
                ->getOneOrNullResult();
        }catch (NonUniqueResultException|\Exception $exception){
            $this->logger->error($exception->getMessage());
        }

        return $supervisor?? null;
    }

//    /**
//     * @return Supervisor[] Returns an array of Supervisor objects
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

//    public function findOneBySomeField($value): ?Supervisor
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
