<?php

namespace App\Repository;

use App\Entity\UserCommands;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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
}
