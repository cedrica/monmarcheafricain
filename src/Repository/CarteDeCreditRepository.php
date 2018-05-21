<?php

namespace App\Repository;

use App\Entity\CarteDeCredit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CarteDeCredit|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarteDeCredit|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarteDeCredit[]    findAll()
 * @method CarteDeCredit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarteDeCreditRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarteDeCredit::class);
    }

//    /**
//     * @return CarteDeCredit[] Returns an array of CarteDeCredit objects
//     */
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
    public function findOneBySomeField($value): ?CarteDeCredit
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
