<?php

namespace App\Repository;

use App\Entity\Appointment;
use App\Entity\Patient;
use App\Entity\Specialist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Appointment> */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    /** @return Appointment[] */
    public function findForPatient(Patient $patient): array
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.specialist', 's')
            ->addSelect('s')
            ->where('a.patient = :patient')
            ->setParameter('patient', $patient)
            ->orderBy('a.scheduledAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /** @return Appointment[] */
    public function findForSpecialist(Specialist $specialist): array
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.patient', 'p')
            ->addSelect('p')
            ->where('a.specialist = :specialist')
            ->setParameter('specialist', $specialist)
            ->orderBy('a.scheduledAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
