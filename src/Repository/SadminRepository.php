<?php

namespace App\Repository;

use App\Entity\Sadmin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sadmin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sadmin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sadmin[]    findAll()
 * @method Sadmin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SadminRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sadmin::class);
    }

    // /**
    //  * @return Sadmin[] Returns an array of Sadmin objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sadmin
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
