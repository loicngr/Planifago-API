<?php

namespace App\Repository;

use App\Entity\PlanOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlanOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanOption[]    findAll()
 * @method PlanOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanOption::class);
    }

    // /**
    //  * @return PlanOption[] Returns an array of PlanOption objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlanOption
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
