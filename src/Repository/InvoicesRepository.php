<?php

namespace App\Repository;

use App\Entity\Invoices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Invoices|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoices|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoices[]    findAll()
 * @method Invoices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoicesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoices::class);
    }

    /**
     * @param $user
     * @return Invoices[] Returns an array of Invoices objects
     */
    public function findByUser($user)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.user = :val')
            ->setParameter('val', $user)
            ->orderBy('i.invoice_at', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Invoices
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
