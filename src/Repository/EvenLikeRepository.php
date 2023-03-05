<?php

namespace App\Repository;

use App\Entity\EvenLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EvenLike>
 *
 * @method EvenLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method EvenLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method EvenLike[]    findAll()
 * @method EvenLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvenLike::class);
    }

    public function save(EvenLike $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EvenLike $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}