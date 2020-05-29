<?php

namespace App\Repository;

use App\Entity\UserAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAddress[]    findAll()
 * @method UserAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAddress::class);
    }

    /**
     * @param $user
     * @return int|mixed|string
     */
    public function findByUser($user)
    {
        return $this->createQueryBuilder('ua')
            ->where('ua.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $user
     * @return null|UserAddress
     * @throws NonUniqueResultException
     */
    public function findByUserAndCommand($user): ? UserAddress
    {
        return $this->createQueryBuilder('ua')
            ->where('ua.user = :user')
            ->setParameter('user', $user)
            ->andWhere('ua.for_command = 1')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param $user
     * @return int|mixed|string
     */
    public function findByUserAndCommandWithArray($user)
    {
        return $this->createQueryBuilder('ua')
            ->where('ua.user = :user')
            ->setParameter('user', $user)
            ->andWhere('ua.for_command = 1')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $user
     * @return null|UserAddress
     * @throws NonUniqueResultException
     */
    public function findByUserAndBilling($user): ? userAddress
    {
        return $this->createQueryBuilder('ua')
            ->where('ua.user = :user')
            ->setParameter('user', $user)
            ->andWhere('ua.for_billing = 1')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param $user
     * @return int|mixed|string
     */
    public function findByUserAndBillingWithArray($user)
    {
        return $this->createQueryBuilder('ua')
            ->where('ua.user = :user')
            ->setParameter('user', $user)
            ->andWhere('ua.for_billing = 1')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $user
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findByUserAndCount($user)
    {
        return $this->createQueryBuilder('ua')
            ->select('COUNT(ua)')
            ->where('ua.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
}
