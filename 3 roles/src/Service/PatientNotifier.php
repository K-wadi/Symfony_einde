<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Entity\PatientNotification;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Stuurt een bericht naar de patiënt (opdracht stap 8).
 */
class PatientNotifier
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function notifyAppointmentChanged(Appointment $appointment, string $action): void
    {
        $patient = $appointment->getPatient();
        if (!$patient) {
            return;
        }

        $date = $appointment->getScheduledAt()?->format('d-m-Y H:i') ?? '-';
        $specialistName = $appointment->getSpecialist()?->getFullName() ?? 'specialist';

        $notification = new PatientNotification();
        $notification->setPatient($patient);
        $notification->setMessage(sprintf(
            'Je afspraak op %s met %s is %s.',
            $date,
            $specialistName,
            $action
        ));
        $notification->setCreatedAt(new \DateTimeImmutable());
        $notification->setIsRead(false);

        $this->entityManager->persist($notification);
    }
}
