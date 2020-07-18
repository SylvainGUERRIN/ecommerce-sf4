<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @method Post[]
     * @return Query
     * @throws \Exception
     */
    public function findAllRecent(): Query
    {
        return $this->createQueryBuilder('c')
            ->where('c.comment_at <= :date')
            ->setParameter('date', new \DateTime(date('Y-m-d H:i:s')))
            ->orderBy('c.comment_at','DESC')
            ->getQuery();
    }

    /**
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countNbComments()
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param $post
     * @return int|mixed|string
     */
    public function findByPost($post)
    {
        return $this->createQueryBuilder('c')
            ->where('c.post = :post')
            ->setParameter('post', $post)
            ->AndWhere('c.valid = true')
            ->getQuery()
            ->getResult()
            ;
    }
}
