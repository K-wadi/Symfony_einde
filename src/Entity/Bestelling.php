<?php

namespace App\Entity;

use App\Repository\BestellingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BestellingRepository::class)]
class Bestelling
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $klantnaam = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datum = null;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\ManyToMany(targetEntity: Product::class)]
    #[ORM\JoinTable(name: 'bestelling_product')]
    private Collection $producten;

    public function __construct()
    {
        $this->producten = new ArrayCollection();
        $this->datum = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKlantnaam(): ?string
    {
        return $this->klantnaam;
    }

    public function setKlantnaam(string $klantnaam): static
    {
        $this->klantnaam = $klantnaam;

        return $this;
    }

    public function getDatum(): ?\DateTimeInterface
    {
        return $this->datum;
    }

    public function setDatum(\DateTimeInterface $datum): static
    {
        $this->datum = $datum;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducten(): Collection
    {
        return $this->producten;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->producten->contains($product)) {
            $this->producten->add($product);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        $this->producten->removeElement($product);

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('Bestelling #%d - %s', $this->id ?? 0, $this->klantnaam ?? '');
    }
}
