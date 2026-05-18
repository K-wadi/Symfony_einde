<?php

namespace App\Controller;

use App\Repository\SalonProductRepository;
use App\Storage\CartSessionStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Webwinkel: producten bekijken en in winkelwagen leggen.
class SalonProductController extends AbstractController
{
    #[Route('/producten', name: 'kapsalon_products')]
    public function index(SalonProductRepository $repository): Response
    {
        return $this->render('kapsalon/product/index.html.twig', [
            'products' => $repository->findBy([], ['category' => 'ASC', 'name' => 'ASC']),
        ]);
    }

    #[Route('/producten/{id}/in-winkelwagen', name: 'kapsalon_cart_add', requirements: ['id' => '\d+'])]
    public function addToCart(int $id, CartSessionStorage $cart): Response
    {
        $cart->addProduct($id);
        $this->addFlash('success', 'Product toegevoegd aan winkelwagen.');

        return $this->redirectToRoute('kapsalon_products');
    }
}
