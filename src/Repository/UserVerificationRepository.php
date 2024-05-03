<?php

namespace App\Repository;

use App\Entity\UserVerification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserVerification>
 *
 * @method UserVerification|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserVerification|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserVerification[]    findAll()
 * @method UserVerification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserVerificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserVerification::class);
    }

    //    /**
    //     * @return UserVerification[] Returns an array of UserVerification objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserVerification
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
