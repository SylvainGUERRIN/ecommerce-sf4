<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
        $this->paginator = $paginator;
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
            ->join('p.product_image', 'pi')
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
            ->join('p.product_image', 'pi')
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
     * @param $productName
     * @return array
     */
    public function findAllByName($productName): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.name IN (:name)')
            ->setParameter('name', $productName)
            ->getQuery()
            ->getResult()
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

    /**
     * Récupére les produits en lien avec les recherches
     * @param SearchData $search
     * @return PaginationInterface
     */
    public function findSearch(SearchData $search): PaginationInterface
    {
        $query = $this->getSearchQuery($search)->getQuery();
        return $this->paginator->paginate($query, $search->page, 6);

//        return $this->findAll();
    }

    /**
     * Récupére le prix min et max en fonction de la recherche
     * @param SearchData $search
     * @return integer[]
     */
    public function findMinMax(SearchData $search): array
    {
        $results = $this->getSearchQuery($search, true)
            ->select('MIN(p.price) as min','MAX(p.price) as max')
            ->getQuery()
            ->getScalarResult();
        return [(int)$results[0]['min'], (int)$results[0]['max']];
    }

    /**
     * @param SearchData $search
     * @param bool $ignorePrice
     * @return QueryBuilder
     */
    private function getSearchQuery(SearchData $search, $ignorePrice = false): QueryBuilder
    {
        $query = $this->createQueryBuilder('p')
            ->select('c','p','pi')
            ->join('p.category', 'c')
            ->join('p.product_image', 'pi');
        if(!empty($search->q)){
            $query = $query
                ->andWhere('p.name LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }
        if(!empty($search->min) && $ignorePrice === false){
            $query = $query
                ->andWhere('p.price >= :min')
                ->setParameter('min', $search->min);
        }
        if(!empty($search->max) && $ignorePrice === false){
            $query = $query
                ->andWhere('p.price <= :max')
                ->setParameter('max', $search->max);
        }
        if(!empty($search->promo)){
            $query = $query
                ->andWhere('p.promo IS NOT NULL');
        }
        if(!empty($search->categories)){
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }
        return $query;
    }
}
