<?php

namespace App\Repository;

use App\Entity\UserCommands;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
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

    /**
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countNbUserCommands()
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param $user
     * @return int|mixed|string|null
     */
    public function findByUser($user)
    {
        return $this->createQueryBuilder('u')
            ->where('u.user = :user')
            ->setParameter('user', $user)
            ->orderBy('u.command_at','DESC')
            ->getQuery();
//            ->getResult();
    }

    /**
     * @method UserCommands[]
     * @return Query
     * @throws \Exception
     */
    public function findAllRecent(): Query
    {

        return $this->createQueryBuilder('u')
            ->where('u.command_at <= :date')
            ->setParameter('date', new \DateTime(date('Y-m-d H:i:s')))
            ->andWhere('u.sent IS NULL')
//            ->setParameter()
            ->orderBy('u.command_at','DESC')
            ->getQuery();
//            ->getResult();
    }

    /**
     * @method UserCommands[]
     * @return Query
     * @throws \Exception
     */
    public function findAllArchives(): Query
    {

        return $this->createQueryBuilder('u')
            ->where('u.command_at <= :date')
            ->setParameter('date', new \DateTime(date('Y-m-d H:i:s')))
            ->andWhere('u.sent = TRUE')
            ->orderBy('u.command_at','DESC')
            ->getQuery();
    }

    /**
     * @method UserCommands[]
     * @return Query
     * @throws \Exception
     */
    public function findAllToSent(): Query
    {

        return $this->createQueryBuilder('u')
            ->where('u.command_at <= :date')
            ->setParameter('date', new \DateTime(date('Y-m-d H:i:s')))
//            ->andWhere()
//            ->setParameter()
            ->orderBy('u.command_at','DESC')
            ->getQuery();
//            ->getResult();
    }

    /**
     * @param $user
     * @return int|mixed|string
     * @throws NonUniqueResultException
     */
    public function findByUserNoValidateNoPaid($user)
    {
        return $this->createQueryBuilder('u')
            ->where('u.user = :user')
            ->andWhere('u.validate = false')
            ->andWhere('u.paid = false')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $user
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function findByUserValidateNoPaid($user)
    {
        return $this->createQueryBuilder('u')
            ->where('u.user = :user')
            ->andWhere('u.validate = true')
            ->andWhere('u.paid = false')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
