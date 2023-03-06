<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineExtensions\Query\Mysql\UnixTimestamp;

/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    public function save(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    /*public function findReclamationByType($Type){
        return $this->createQueryBuilder('reclamation')
                    ->where('reclamation.Type LIKE :Type')
                     ->setParameter('Type','%'.$Type.'%')
                    ->getQuery()
                    ->getResult();
}
*/

public function findReclamationByType($searchValue)
{
    $qb = $this->createQueryBuilder('r')
        ->Where('r.Type LIKE :searchValue')
        ->orWhere('r.Description LIKE :searchValue')
        ->orWhere('r.Date LIKE :searchValue')
        ->setParameter('searchValue', '%'.$searchValue.'%');

    $query = $qb->getQuery();
    $sql = $query->getSQL();
    

    return $query->getResult();
}


public function findAllSortedByDateAsc()
    {
        return $this->createQueryBuilder('r')
        ->orderBy('r.Date', 'DESC')
        ->addOrderBy('r.id', 'DESC')
        ->getQuery()
        ->getResult();
    }


    public function findReclamationByTraite($searchValue)
{
    $qb = $this->createQueryBuilder('r');
    
    $subquery = $qb->getEntityManager()->createQueryBuilder();
    $subquery->select('MAX(reponse.id)')
             ->from('App\Entity\Reponse', 'reponse')
             ->where('reponse.reclamation = r');
             
    $qb->leftJoin('r.reponses', 'lastReponse', 'WITH', 'lastReponse.id = (' . $subquery->getDQL() . ')')
       ->andWhere('lastReponse.Etat = :searchValue')
       ->setParameter('searchValue', $searchValue);

    $query = $qb->getQuery();
    $result = $query->getResult();

    return $result;
}
    



//    /**
//     * @return Reclamation[] Returns an array of Reclamation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reclamation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


}
