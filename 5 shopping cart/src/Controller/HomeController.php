<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Storage\CartSessionStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Producten homepage (stap 8 + 10).
 */
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /** Buy-knop (stap 12). */
    #[Route('/product/add/{id}', name: 'app_product_add', requirements: ['id' => '\d+'])]
    public function addProduct(int $id, CartSessionStorage $cartStorage): Response
    {
        $cartStorage->addProductToCart($id);
        $this->addFlash('success', 'Product toegevoegd aan je winkelwagen.');

        return $this->redirectToRoute('app_home');
    }
}
