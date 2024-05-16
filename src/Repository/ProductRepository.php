<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
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

    //    /**
    //     * @return Product[] Returns an array of Product objects
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

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function searchAndFilter($title, $max_price,$min_price, $isUsed,$orderby): array
    {
        $queryBuilder = $this->createQueryBuilder('p');

        // Apply title search
        if ($title) {
            $queryBuilder->andWhere('p.name LIKE :title')
                ->setParameter('title', '%' . $title . '%');
        }

        // Apply price filter
        if ($max_price) {
            $queryBuilder->andWhere('p.price <= :max_price')
                ->setParameter('max_price', $max_price);
        }

        if ($min_price) {
            $queryBuilder->andWhere('p.price >= :min_price')
                ->setParameter('min_price', $min_price);
        }

        // Apply is_used filter
        if ($isUsed !== null && $isUsed !== '') {
            $queryBuilder->andWhere('p.isUsed = :isUsed')
                ->setParameter('isUsed', $isUsed);
        }

        $queryBuilder->andWhere('p.status = :status')
            ->setParameter('status', 'accepted');

        if($orderby == 'price_asc'){
            $queryBuilder->orderBy('p.price', 'ASC');
        }else{
            $queryBuilder->orderBy('p.price', 'DESC');
        }

        $queryBuilder->andWhere('p.quantity > 0');

        // Return a Paginator object
        return $queryBuilder->getQuery()->getResult();
    }

}
