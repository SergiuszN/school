<?php

namespace App\Repository;

use App\Entity\Event;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @return Event[]
     */
    public function findUpcomings()
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.date > :from')
            ->andWhere('e.date < :to')
            ->orderBy('e.date', 'ASC')
            ->setParameter('from', (new DateTime())->format('Y-m-d'))
            ->setParameter('to', (new DateTime())->modify(Event::AVAILABLE_FOR_REGISTRATION)->format('Y-m-d'))
            ->getQuery()
            ->execute();
    }
}
