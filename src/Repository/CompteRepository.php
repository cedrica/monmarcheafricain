<?php

namespace App\Repository;

use App\Entity\Compte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Compte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Compte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Compte[]    findAll()
 * @method Compte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Compte::class);
    }

//    /**
//     * @return Compte[] Returns an array of Compte objects
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
    public function findByLoginId($loginId): ?Login
    {
    	$arr =  $this->createQueryBuilder('c')
    	// p.category refers to the "category" property on product
    	->leftJoin('c.login', 'l')
    	// selects all the category data to avoid the query
    	->select('l')
    	->where('l.id = :loginId')
    	->setParameter('loginId', $loginId)
    	->getQuery()
    	->getOneOrNullResult();
    	return $arr;
    }
    
}
