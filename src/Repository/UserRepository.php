<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $mail
     * @return null|User
     * @throws NonUniqueResultException
     */
    public function findByMail($mail): ?User
    {
        return $this->createQueryBuilder('u')
            ->setParameter('mail', $mail)
            ->andWhere('u.mail = :mail')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
