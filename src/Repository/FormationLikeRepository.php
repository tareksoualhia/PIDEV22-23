<?php

namespace App\Repository;

use App\Entity\FormationLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<formationLike>
 *
 * @method formationLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method formationLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method formationLike[]    findAll()
 * @method formationLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormationLike::class);
    }

    public function save(FormationLike $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FormationLike $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}