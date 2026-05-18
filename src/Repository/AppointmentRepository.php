<?php

namespace App\Repository;

use App\Entity\Appointment;
use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Appointment> */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    // Link in e-mail/SMS om afspraak te wijzigen of annuleren.
    public function findOneByToken(string $token): ?Appointment
    {
        return $this->findOneBy(['manageToken' => $token]);
    }

    public function findForEmployee(Employee $employee): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.employee = :employee')
            ->setParameter('employee', $employee)
            ->orderBy('a.startsAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Aantal afspraken per klant-e-mail voor statistieken.
    public function countByCustomerEmail(): array
    {
        $rows = $this->createQueryBuilder('a')
            ->select('a.customerEmail AS email, COUNT(a.id) AS cnt')
            ->groupBy('a.customerEmail')
            ->getQuery()
            ->getArrayResult();

        $result = [];
        foreach ($rows as $row) {
            $result[$row['email']] = (int) $row['cnt'];
        }

        return $result;
    }
}
