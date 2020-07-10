<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @method Post[]
     * @return Query
     * @throws \Exception
     */
    public function findAllRecent(): Query
    {
        return $this->createQueryBuilder('p')
            ->where('p.created_at <= :date')
            ->setParameter('date', new \DateTime(date('Y-m-d H:i:s')))
            ->orderBy('p.created_at','DESC')
            ->getQuery();
//            ->getResult();
    }
}
