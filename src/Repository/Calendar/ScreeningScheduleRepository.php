<?php

namespace App\Repository\Calendar;

use App\Entity\Calendar\ScreeningSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ScreeningSchedule>
 *
 * @method ScreeningSchedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScreeningSchedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScreeningSchedule[]    findAll()
 * @method ScreeningSchedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScreeningScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScreeningSchedule::class);
    }

    public function save(ScreeningSchedule $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ScreeningSchedule $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ScreeningSchedule[] Returns an array of ScreeningSchedule objects
//     */
//    public function findByExampleField($start, $end): array
//    {
//        return $this->createQueryBuilder('s')
//            ->where('s.weekStart <= :weekStart')
//            ->andWhere('s.weekEnd >= :weekEnd')
//            ->setParameter('weekStart', $start)
//            ->setParameter('weekEnd', $end)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findOneByDates(\DateTimeImmutable $start, \DateTimeImmutable $end): ?ScreeningSchedule
    {
        return $this->createQueryBuilder('s')
            ->where('s.weekStart <= :weekStart')
            ->andWhere('s.weekEnd >= :weekEnd')
            ->setParameter('weekStart', $start)
            ->setParameter('weekEnd', $end)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
