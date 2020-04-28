<?php

namespace App\Repository;

use App\Entity\UserCommands;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserCommands|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCommands|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCommands[]    findAll()
 * @method UserCommands[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCommandsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserCommands::class);
    }

    // /**
    //  * @return UserCommands[] Returns an array of UserCommands objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserCommands
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
