<?php

namespace App\Repository;

use App\Entity\SmsNotification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<SmsNotification> */
class SmsNotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SmsNotification::class);
    }

    // Laatste verzonden SMS voor admin-overzicht.
    public function findLatest(int $limit = 50): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.sentAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
