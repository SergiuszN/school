<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\EventRegistration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventRegistration|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventRegistration|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventRegistration[]    findAll()
 * @method EventRegistration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventRegistration::class);
    }

    public function isRegistered($email, Event $event): bool
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->createQueryBuilder('er')
            ->andWhere('er.email = :email')
            ->andWhere('er.event = :event')
            ->setParameter('email', $email)
            ->setParameter('event', $event)
            ->getQuery()
            ->getOneOrNullResult() instanceof EventRegistration;
    }
}
