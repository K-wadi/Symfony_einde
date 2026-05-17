<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Supertype User – Single Table Inheritance (opdracht PDF + presentatie roles 2).
 * Patient en Specialist zijn subtypes in dezelfde user-tabel.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'user_type', type: 'string')]
#[ORM\DiscriminatorMap([
    'patient' => Patient::class,
    'specialist' => Specialist::class,
    'admin' => AdminUser::class,
])]
#[UniqueEntity(fields: ['email'], message: 'Dit e-mailadres is al in gebruik.')]
abstract class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    protected ?string $email = null;

    #[ORM\Column]
    protected array $roles = [];

    #[ORM\Column]
    protected ?string $password = null;

    #[ORM\Column(name: 'first_name', length: 255, nullable: true)]
    protected ?string $firstName = null;

    #[ORM\Column(name: 'last_name', length: 255, nullable: true)]
    protected ?string $lastName = null;

    /** Velden patiënt (presentatie – alle kolommen in één tabel). */
    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $address = null;

    #[ORM\Column(name: 'zip_code', length: 20, nullable: true)]
    protected ?string $zipCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $city = null;

    /** Veld specialist. */
    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $specialization = null;

    /**
     * Relatie: alle afspraken waar deze user de patiënt is (opdracht PDF stap 2.4a).
     *
     * @var Collection<int, Appointment>
     */
    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Appointment::class)]
    protected Collection $patientAppointments;

    /**
     * Relatie: alle afspraken waar deze user de specialist is (opdracht PDF stap 2.4b).
     *
     * @var Collection<int, Appointment>
     */
    #[ORM\OneToMany(mappedBy: 'specialist', targetEntity: Appointment::class)]
    protected Collection $specialistAppointments;

    public function __construct()
    {
        $this->patientAppointments = new ArrayCollection();
        $this->specialistAppointments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getFullName(): string
    {
        return trim(($this->firstName ?? '').' '.($this->lastName ?? ''));
    }

    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

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

    /** @return Collection<int, Appointment> */
    public function getPatientAppointments(): Collection
    {
        return $this->patientAppointments;
    }

    /** @return Collection<int, Appointment> */
    public function getSpecialistAppointments(): Collection
    {
        return $this->specialistAppointments;
    }
}
