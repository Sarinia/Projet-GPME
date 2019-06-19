<?php

namespace App\Repository;

use App\Entity\Cardsp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Cardsp|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cardsp|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cardsp[]    findAll()
 * @method Cardsp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardspRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cardsp::class);
    }

    // /**
    //  * @return Cardsp[] Returns an array of Cardsp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cardsp
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
