<?php

namespace App\Service;

use App\Entity\Employee;
use App\Entity\Treatment;
use App\Repository\AppointmentRepository;
use App\Repository\EmployeeRepository;
use App\Repository\TimeBlockRepository;

// Berekent vrije tijdslots voor reserveren op basis van kapster, tijdsblokken en bestaande afspraken.
class AvailabilityService
{
    public function __construct(
        private readonly EmployeeRepository $employeeRepository,
        private readonly AppointmentRepository $appointmentRepository,
        private readonly TimeBlockRepository $timeBlockRepository,
    ) {
    }

    // Geeft beschikbare tijden terug; key = kapster|datum-tijd voor het formulier.
    public function getAvailableSlots(\DateTimeImmutable $day, ?Employee $employee, Treatment $treatment): array
    {
        $employees = $employee ? [$employee] : $this->employeeRepository->findAll();
        $slots = [];
        $duration = $treatment->getDurationMinutes();

        foreach ($employees as $emp) {
            foreach ($this->generateSlotsForEmployee($emp, $day, $duration) as $value => $label) {
                $slots[$value] = $label;
            }
        }

        if ($slots === [] && $employees !== []) {
            return [];
        }

        ksort($slots);

        return $slots;
    }

    public function hasAnyAvailability(\DateTimeImmutable $day, Treatment $treatment): bool
    {
        return $this->getAvailableSlots($day, null, $treatment) !== [];
    }

    // Maakt slots per kapster; zonder tijdsblok standaard 9:00–17:00.
    private function generateSlotsForEmployee(Employee $employee, \DateTimeImmutable $day, int $durationMinutes): array
    {
        $blocks = $this->timeBlockRepository->findBy(['employee' => $employee]);
        $slots = [];

        if ($blocks === []) {
            $this->addSlotsForRange($slots, $employee, $day->setTime(9, 0), $day->setTime(17, 0), $durationMinutes);

            return $slots;
        }

        foreach ($blocks as $block) {
            $start = $block->getStartAt() ?? $day->setTime(9, 0);
            $end = $block->getEndAt() ?? $day->setTime(17, 0);
            $this->addSlotsForRange($slots, $employee, $start, $end, $durationMinutes);
        }

        return $slots;
    }

    private function addSlotsForRange(array &$slots, Employee $employee, \DateTimeImmutable $start, \DateTimeImmutable $end, int $durationMinutes): void
    {
        $cursor = $start;
        while ($cursor < $end) {
            $slotEnd = $cursor->modify(sprintf('+%d minutes', $durationMinutes));
            if ($slotEnd > $end) {
                break;
            }
            if (!$this->isSlotTaken($employee, $cursor, $slotEnd)) {
                $key = $employee->getId().'|'.$cursor->format('Y-m-d H:i');
                $slots[$key] = sprintf('%s — %s', $employee->getName(), $cursor->format('d-m-Y H:i'));
            }
            $cursor = $cursor->modify('+30 minutes');
        }
    }

    private function isSlotTaken(Employee $employee, \DateTimeImmutable $start, \DateTimeImmutable $end): bool
    {
        foreach ($this->appointmentRepository->findForEmployee($employee) as $appointment) {
            if ($appointment->getStatus() !== 'planned') {
                continue;
            }
            $apptStart = $appointment->getStartsAt();
            if ($apptStart >= $start && $apptStart < $end) {
                return true;
            }
        }

        return false;
    }
}
