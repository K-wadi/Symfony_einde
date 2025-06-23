<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        // Publieke pagina - alle bezoekers kunnen producten bekijken
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
        
        // TODO: Twig template aanmaken voor product overzicht
        // - Toon alle producten in cards/grid layout
        // - Naam, prijs, beschrijving per product
        // - Responsive design
        // - Link naar bestelformulier (voor ingelogde gebruikers)
    }

    #[Route('/admin', name: 'app_product_admin', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function admin(ProductRepository $productRepository): Response
    {
        // Alleen voor admins - beheer overzicht
        return $this->render('product/admin.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
        
        // TODO: Twig template aanmaken voor admin product beheer
        // - Tabel met alle producten
        // - Edit/Delete knoppen per product
        // - Link naar "nieuw product" toevoegen
        // - Zoek/filter functionaliteit
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Product succesvol toegevoegd!');

            return $this->redirectToRoute('app_product_admin');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
        
        // TODO: Twig template aanmaken voor nieuw product form
        // - Naam, prijs, beschrijving velden
        // - Validatie errors weergeven
        // - Cancel knop terug naar admin overzicht
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        // Publieke detailpagina van een product
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
        
        // TODO: Twig template aanmaken voor product detail
        // - Alle product informatie weergeven
        // - Terug naar overzicht knop
        // - "Bestel dit product" knop (voor ingelogde gebruikers)
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Product succesvol bijgewerkt!');

            return $this->redirectToRoute('app_product_admin');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
        
        // TODO: Twig template aanmaken voor product bewerken
        // - Zelfde form als new, maar met bestaande data
        // - Delete knop toevoegen
        // - Cancel knop terug naar admin overzicht
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash('success', 'Product succesvol verwijderd!');
        }

        return $this->redirectToRoute('app_product_admin');
    }
} 