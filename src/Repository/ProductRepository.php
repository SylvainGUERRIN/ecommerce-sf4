<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @method Product[]
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

    /**
     * @method Product[]
     * @param $category
     * @return Query
     * @throws \Exception
     */
    public function findAllRecentWithCategory($category): Query
    {
        return $this->createQueryBuilder('p')
            ->where('p.created_at <= :date')
            ->setParameter('date', new \DateTime(date('Y-m-d H:i:s')))
            ->andWhere('p.category = :category')
            ->setParameter('category', $category)
            ->orderBy('p.created_at','DESC')
            ->getQuery();
//            ->getResult();
    }

    /**
     * @param $limit
     * @return int|mixed|string
     */
    public function findLatestWithLimit($limit)
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.created_at','DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countNbProducts()
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param int $limit
     * @return int|mixed|string
     */
    public function findAllWithLimit(int $limit)
    {
        return $this->createQueryBuilder('p')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $slug
     * @return Product
     * @throws NonUniqueResultException
     */
    public function findProductWithSlug($slug): Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param $productName
     * @return Product
     * @throws NonUniqueResultException
     */
    public function findByName($productName): Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name = :name')
            ->setParameter('name', $productName)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param $productSlug
     * @return Product
     * @throws NonUniqueResultException
     */
    public function findBySlug($productSlug): Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.slug = :slug')
            ->setParameter('slug', $productSlug)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
