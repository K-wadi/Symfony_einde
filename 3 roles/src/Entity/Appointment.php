<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Afspraak – twee relaties naar User (patient + specialist), opdracht PDF.
 */
#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $scheduledAt = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $notes = null;

    /** Relatie-eigenschap patient (opdracht PDF 2.4). */
    #[ORM\ManyToOne(inversedBy: 'patientAppointments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Kies een patiënt.')]
    private ?Patient $patient = null;

    /** Relatie-eigenschap specialist (opdracht PDF 2.4). */
    #[ORM\ManyToOne(inversedBy: 'specialistAppointments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Specialist is verplicht.')]
    private ?Specialist $specialist = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScheduledAt(): ?\DateTimeInterface
    {
        return $this->scheduledAt;
    }

    public function setScheduledAt(\DateTimeInterface $scheduledAt): static
    {
        $this->scheduledAt = $scheduledAt;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getSpecialist(): ?Specialist
    {
        return $this->specialist;
    }

    public function setSpecialist(?Specialist $specialist): static
    {
        $this->specialist = $specialist;

        return $this;
    }
}
