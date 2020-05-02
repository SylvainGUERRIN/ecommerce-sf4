<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @method Article[]
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
