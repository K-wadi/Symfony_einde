<?php

namespace App\Entity;

use App\Repository\ShopOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

// Bestelling uit de webwinkel met regels en afleverwijze.
#[ORM\Entity(repositoryClass: ShopOrderRepository::class)]
class ShopOrder
{
    public const DELIVERY_HOME = 'delivery';
    public const DELIVERY_PICKUP = 'pickup';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private ?string $customerName = null;

    #[ORM\Column(length: 180)]
    private ?string $customerEmail = null;

    #[ORM\Column(length: 20)]
    private string $deliveryMethod = self::DELIVERY_PICKUP;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /** @var Collection<int, ShopOrderLine> */
    #[ORM\OneToMany(mappedBy: 'shopOrder', targetEntity: ShopOrderLine::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $lines;

    public function __construct()
    {
        $this->lines = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(string $customerName): static
    {
        $this->customerName = $customerName;

        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail(string $customerEmail): static
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    public function getDeliveryMethod(): string
    {
        return $this->deliveryMethod;
    }

    public function setDeliveryMethod(string $deliveryMethod): static
    {
        $this->deliveryMethod = $deliveryMethod;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /** @return Collection<int, ShopOrderLine> */
    public function getLines(): Collection
    {
        return $this->lines;
    }

    public function addLine(ShopOrderLine $line): static
    {
        if (!$this->lines->contains($line)) {
            $this->lines->add($line);
            $line->setShopOrder($this);
        }

        return $this;
    }

    public function getTotalCents(): int
    {
        $total = 0;
        foreach ($this->lines as $line) {
            $total += $line->getProduct()->getPriceCents() * $line->getQuantity();
        }

        return $total;
    }
}
