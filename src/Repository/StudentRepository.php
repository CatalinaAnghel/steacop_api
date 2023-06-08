<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\Student;
use App\Entity\Supervisor;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Student>
 *
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public const ALIAS = 'student';

    public function __construct(ManagerRegistry $registry, private readonly LoggerInterface $logger)
    {
        parent::__construct($registry, Student::class);
    }

    public function add(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param string $supervisorEmail
     * @return Student[]
     */
    public function findSupervisedStudents(string $supervisorEmail): array
    {
        $qb = $this->createQueryBuilder(self::ALIAS)
            ->innerJoin(
                Project::class,
                'project',
                Join::WITH,
                'project.id = ' . self::ALIAS . '.project'
            )
            ->innerJoin(
                Supervisor::class,
                'supervisor',
                Join::WITH,
                'supervisor.id = project.supervisor'
            )
            ->innerJoin(
                User::class,
                'user',
                Join::WITH,
                'user.id = supervisor.user'
            )
            ->where('user.email = :email')
            ->setParameter('email', $supervisorEmail);

        try {
            $results = $qb->getQuery()->getResult();
        } catch (\Exception $exception) {
            dd('here', $exception->getMessage());
            $this->logger->error($exception->getMessage());
        }

        return $results ?? [];
    }

    //    /**
    //     * @return Student[] Returns an array of Student objects
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

    //    public function findOneBySomeField($value): ?Student
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
