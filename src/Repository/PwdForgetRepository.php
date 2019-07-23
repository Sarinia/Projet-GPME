<?php

namespace App\Repository;

use App\Entity\PwdForget;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PwdForget|null find($id, $lockMode = null, $lockVersion = null)
 * @method PwdForget|null findOneBy(array $criteria, array $orderBy = null)
 * @method PwdForget[]    findAll()
 * @method PwdForget[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PwdForgetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PwdForget::class);
    }

    // /**
    //  * @return PwdForget[] Returns an array of PwdForget objects
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
    public function findOneBySomeField($value): ?PwdForget
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
