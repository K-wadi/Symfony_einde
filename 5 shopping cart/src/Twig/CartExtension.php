<?php

namespace App\Twig;

use App\Storage\CartSessionStorage;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

/** Cart-teller in navbar (stap 9 header block). */
class CartExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly CartSessionStorage $cartStorage,
    ) {
    }

    public function getGlobals(): array
    {
        return [
            'cart_count' => $this->cartStorage->getNumberOfProductInCart(),
            'cart_total' => $this->cartStorage->getTotalPrice(),
        ];
    }
}
