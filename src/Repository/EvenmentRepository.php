<?php

namespace App\Repository;

use App\Entity\Evenment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evenment>
 *
 * @method Evenment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenment[]    findAll()
 * @method Evenment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenment::class);
    }

    public function save(Evenment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Evenment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Evenment[] Returns an array of Evenment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Evenment
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findByTitle($title)
    {
        return $this->createQueryBuilder('evenment')
            ->andWhere('evenment.Title LIKE :Title')
            ->setParameter('Title', '%' . $title . '%')
            ->getQuery()
            ->getResult();


    }


}

