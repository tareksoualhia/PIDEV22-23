<?php

namespace App\Repository;

use App\Entity\Calendar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Calendar>
 *
 * @method Calendar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calendar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calendar[]    findAll()
 * @method Calendar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calendar::class);
    }

    public function save(Calendar $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Calendar $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}