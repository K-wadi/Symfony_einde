<?php

namespace App\Entity;

use App\Repository\VendorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Vendor = fabrikant / merk (Many smartphones -> One vendor).
 * Relatie uit presentatie relations + opdracht PDF.
 */
#[ORM\Entity(repositoryClass: VendorRepository::class)]
class Vendor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Naam van de vendor is verplicht.')]
    private ?string $name = null;

    /** @var Collection<int, Smartphone> */
    #[ORM\OneToMany(targetEntity: Smartphone::class, mappedBy: 'vendor')]
    private Collection $smartphones;

    public function __construct()
    {
        $this->smartphones = new ArrayCollection();
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

    /** Gebruikt in EntityType dropdown (vendor keuze in formulier). */
    public function __toString(): string
    {
        return $this->name ?? '';
    }

    /** @return Collection<int, Smartphone> */
    public function getSmartphones(): Collection
    {
        return $this->smartphones;
    }
}
