<?php

namespace App\Repository;

use App\Entity\Profesores;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Profesores>
 *
 * @method Profesores|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profesores|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profesores[]    findAll()
 * @method Profesores[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfesoresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profesores::class);
    }

//    /**
//     * @return Profesores[] Returns an array of Profesores objects
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

//    public function findOneBySomeField($value): ?Profesores
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
