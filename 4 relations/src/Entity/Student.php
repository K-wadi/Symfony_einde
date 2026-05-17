<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Student – Many-to-Many met SchoolGroup (opdracht 1.4).
 * Let op: "klas" = Group, niet "Class" (programmeertaal).
 */
#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /** @var Collection<int, SchoolGroup> */
    #[ORM\ManyToMany(targetEntity: SchoolGroup::class, inversedBy: 'students')]
    private Collection $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /** @return Collection<int, SchoolGroup> */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(SchoolGroup $group): static
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
            $group->addStudent($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
