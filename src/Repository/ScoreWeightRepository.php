<?php

namespace App\Repository;

use App\Entity\ScoreWeight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ScoreWeight>
 *
 * @method ScoreWeight|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScoreWeight|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScoreWeight[]    findAll()
 * @method ScoreWeight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreWeightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScoreWeight::class);
    }

    public function add(ScoreWeight $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ScoreWeight $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
