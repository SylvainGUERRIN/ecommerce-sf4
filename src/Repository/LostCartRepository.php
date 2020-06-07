<?php

namespace App\Repository;

use App\Entity\LostCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LostCart|null find($id, $lockMode = null, $lockVersion = null)
 * @method LostCart|null findOneBy(array $criteria, array $orderBy = null)
 * @method LostCart[]    findAll()
 * @method LostCart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LostCartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LostCart::class);
    }

    /**
     * @param $user
     * @return LostCart|null
     * @throws NonUniqueResultException
     */
    public function findByUser($user): ?LostCart
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
