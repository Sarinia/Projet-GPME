<?php

namespace App\Repository;

use App\Entity\Postit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Postit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Postit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Postit[]    findAll()
 * @method Postit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Postit::class);
    }

    // /**
    //  * @return Postit[] Returns an array of Postit objects
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
    public function findOneBySomeField($value): ?Postit
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
