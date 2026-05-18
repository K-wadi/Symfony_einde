<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private ?string $name = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $specialization = null;

    #[ORM\OneToOne(inversedBy: 'employee', targetEntity: User::class, cascade: ['persist'])]
    private ?User $user = null;

    /** @var Collection<int, Appointment> */
    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Appointment::class)]
    private Collection $appointments;

    /** @var Collection<int, TimeBlock> */
    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: TimeBlock::class, orphanRemoval: true)]
    private Collection $timeBlocks;

    public function __construct()
    {
        $this->appointments = new ArrayCollection();
        $this->timeBlocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSpecialization(): ?string
    {
        return $this->specialization;
    }

    public function setSpecialization(?string $specialization): static
    {
        $this->specialization = $specialization;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /** @return Collection<int, Appointment> */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    /** @return Collection<int, TimeBlock> */
    public function getTimeBlocks(): Collection
    {
        return $this->timeBlocks;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
