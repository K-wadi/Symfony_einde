<?php

namespace App\Repository;

use App\Entity\Patient;
use App\Entity\PatientNotification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<PatientNotification> */
class PatientNotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PatientNotification::class);
    }

    /** @return PatientNotification[] */
    public function findUnreadForPatient(Patient $patient): array
    {
        return $this->createQueryBuilder('n')
            ->where('n.patient = :patient')
            ->andWhere('n.isRead = false')
            ->setParameter('patient', $patient)
            ->orderBy('n.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
