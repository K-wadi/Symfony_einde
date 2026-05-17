<?php

namespace App\Storage;

use App\Entity\OrderLine;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Winkelwagen in sessie (opdracht stap 7–8 + ROCMondriaanTIN/shopping-cart).
 * Sessie bevat product-id's: [3,5,5,6,2]
 */
class CartSessionStorage
{
    /** @var OrderLine[]|null */
    private ?array $shoppingCart = null;

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ProductRepository $productRepository,
    ) {
        $this->getShoppingCartFromSession();
    }

    private function getSession()
    {
        return $this->requestStack->getSession();
    }

    /** Herstel winkelwagen uit sessie. */
    private function getShoppingCartFromSession(): void
    {
        $this->shoppingCart = [];
        $products = $this->getSession()->get('cart', []);

        if (!empty($products)) {
            foreach ($products as $productId) {
                $orderLine = $this->orderLineExist((int) $productId);
                if (null !== $orderLine) {
                    $orderLine->setQuantity($orderLine->getQuantity() + 1);
                } else {
                    $orderLine = new OrderLine();
                    $orderLine->setProduct($this->productRepository->find($productId));
                    $orderLine->setQuantity(1);
                    $this->shoppingCart[] = $orderLine;
                }
            }
        }
    }

    private function orderLineExist(int $productId): ?OrderLine
    {
        foreach ($this->shoppingCart as $orderLine) {
            if ($orderLine->getProduct()?->getId() === $productId) {
                return $orderLine;
            }
        }

        return null;
    }

    /** Voeg product toe (stap 12 – buy button). */
    public function addProductToCart(int $productId): void
    {
        $exist = false;

        foreach ($this->shoppingCart as $orderLine) {
            if ($orderLine->getProduct()?->getId() === $productId) {
                $orderLine->setQuantity($orderLine->getQuantity() + 1);
                $exist = true;
                break;
            }
        }

        if (!$exist) {
            $newOrderLine = new OrderLine();
            $newOrderLine->setQuantity(1);
            $newOrderLine->setProduct($this->productRepository->find($productId));
            $this->shoppingCart[] = $newOrderLine;
        }

        $this->setShoppingCartToSession();
    }

    /** Sla cart op als lijst product-id's (één id per stuk). */
    private function setShoppingCartToSession(): void
    {
        $cart = [];
        foreach ($this->shoppingCart as $orderLine) {
            $amount = $orderLine->getQuantity();
            for ($i = 0; $i < $amount; ++$i) {
                $cart[] = $orderLine->getProduct()->getId();
            }
        }
        $this->getSession()->set('cart', $cart);
    }

    public function getNumberOfProductInCart(): int
    {
        $amount = 0;
        foreach ($this->shoppingCart as $orderLine) {
            $amount += $orderLine->getQuantity();
        }

        return $amount;
    }

    public function getTotalPrice(): int
    {
        $amount = 0;
        foreach ($this->shoppingCart as $orderLine) {
            $amount += $orderLine->getProduct()->getPrice() * $orderLine->getQuantity();
        }

        return $amount;
    }

    /** @return OrderLine[]|null */
    public function getShoppingCart(): ?array
    {
        return $this->shoppingCart;
    }

    /** Leeg winkelwagen (stap 15). */
    public function clearShoppingCart(): void
    {
        $this->getSession()->set('cart', []);
        $this->shoppingCart = [];
    }

    /** Stap 16: product verwijderen. */
    public function removeProductFromCart(int $productId): void
    {
        $this->shoppingCart = array_values(array_filter(
            $this->shoppingCart,
            fn (OrderLine $line) => $line->getProduct()?->getId() !== $productId
        ));
        $this->setShoppingCartToSession();
    }

    /** Stap 16: hoeveelheid verhogen. */
    public function increaseQuantity(int $productId): void
    {
        foreach ($this->shoppingCart as $orderLine) {
            if ($orderLine->getProduct()?->getId() === $productId) {
                $orderLine->setQuantity($orderLine->getQuantity() + 1);
                break;
            }
        }
        $this->setShoppingCartToSession();
    }

    /** Stap 16: hoeveelheid verlagen. */
    public function decreaseQuantity(int $productId): void
    {
        foreach ($this->shoppingCart as $key => $orderLine) {
            if ($orderLine->getProduct()?->getId() === $productId) {
                if ($orderLine->getQuantity() <= 1) {
                    unset($this->shoppingCart[$key]);
                    $this->shoppingCart = array_values($this->shoppingCart);
                } else {
                    $orderLine->setQuantity($orderLine->getQuantity() - 1);
                }
                break;
            }
        }
        $this->setShoppingCartToSession();
    }
}
