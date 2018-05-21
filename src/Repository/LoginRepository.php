<?php

namespace App\Repository;

use App\Entity\Login;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Login|null find($id, $lockMode = null, $lockVersion = null)
 * @method Login|null findOneBy(array $criteria, array $orderBy = null)
 * @method Login[]    findAll()
 * @method Login[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoginRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Login::class);
    }

//    /**
//     * @return Login[] Returns an array of Login objects
//     */
    
    public function findByEmailEtMotDePass($email,$motDePass)
    {
        return $this->createQuery('SELECT l
                                FROM App:Login l
                                WHERE l.email = :email and l.motDePass =: motDePass')
                                ->setParameter('email', $email)
                                ->setParameter('motDePass', $motDePass)
        						->getResult();
    }
    public function findOneByEmail($value): ?Login
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.email = :email')
            ->setParameter('email', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
