<?php

namespace App\Storage;

use App\Entity\SalonProduct;
use App\Entity\ShopOrderLine;
use App\Repository\SalonProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

// Winkelwagen in de sessie: product-id's en regels voor afrekenen.
class CartSessionStorage
{
    private array $shoppingCart = [];

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly SalonProductRepository $productRepository,
    ) {
        $this->loadFromSession();
    }

    private function getSession()
    {
        return $this->requestStack->getSession();
    }

    private function loadFromSession(): void
    {
        $this->shoppingCart = [];
        $productIds = $this->getSession()->get('salon_cart', []);
        if (!is_array($productIds) || $productIds === []) {
            return;
        }

        foreach ($productIds as $productId) {
            $line = $this->findLine((int) $productId);
            if ($line !== null) {
                $line->setQuantity($line->getQuantity() + 1);
            } else {
                $product = $this->productRepository->find((int) $productId);
                if ($product instanceof SalonProduct) {
                    $line = new ShopOrderLine();
                    $line->setProduct($product);
                    $line->setQuantity(1);
                    $this->shoppingCart[] = $line;
                }
            }
        }
    }

    private function findLine(int $productId): ?ShopOrderLine
    {
        foreach ($this->shoppingCart as $line) {
            if ($line->getProduct()?->getId() === $productId) {
                return $line;
            }
        }

        return null;
    }

    public function addProduct(int $productId): void
    {
        $line = $this->findLine($productId);
        if ($line !== null) {
            $line->setQuantity($line->getQuantity() + 1);
        } else {
            $product = $this->productRepository->find($productId);
            if ($product instanceof SalonProduct) {
                $line = new ShopOrderLine();
                $line->setProduct($product);
                $line->setQuantity(1);
                $this->shoppingCart[] = $line;
            }
        }
        $this->persistToSession();
    }

    private function persistToSession(): void
    {
        $cart = [];
        foreach ($this->shoppingCart as $line) {
            for ($i = 0; $i < $line->getQuantity(); ++$i) {
                $cart[] = $line->getProduct()->getId();
            }
        }
        $this->getSession()->set('salon_cart', $cart);
    }

    public function getNumberOfProducts(): int
    {
        $count = 0;
        foreach ($this->shoppingCart as $line) {
            $count += $line->getQuantity();
        }

        return $count;
    }

    public function getTotalCents(): int
    {
        $total = 0;
        foreach ($this->shoppingCart as $line) {
            $total += $line->getProduct()->getPriceCents() * $line->getQuantity();
        }

        return $total;
    }

    public function getLines(): array
    {
        return $this->shoppingCart;
    }

    public function clear(): void
    {
        $this->shoppingCart = [];
        $this->getSession()->set('salon_cart', []);
    }
}
