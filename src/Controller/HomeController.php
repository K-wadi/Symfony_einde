<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        // Homepage met uitgelichte producten
        $featuredProducts = $productRepository->findBy([], ['id' => 'DESC'], 6); // Laatste 6 producten
        
        return $this->render('home/index.html.twig', [
            'featured_products' => $featuredProducts,
            'user' => $this->getUser(),
        ]);
        
        // TODO: Twig template aanmaken voor homepage
        // - Welkomstbericht
        // - Uitgelichte producten in grid
        // - Navigatie naar alle producten
        // - Login/Register links (als niet ingelogd)
        // - Admin links (als admin ingelogd)
        // - Bestel knop (als user ingelogd)
    }
} 