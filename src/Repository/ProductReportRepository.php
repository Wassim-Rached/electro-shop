<?php

namespace App\Repository;

use App\Entity\ProductReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductReport>
 *
 * @method ProductReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductReport[]    findAll()
 * @method ProductReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductReport::class);
    }

//    /**
//     * @return ProductReport[] Returns an array of ProductReport objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProductReport
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
