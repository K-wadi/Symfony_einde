<?php

namespace App\Controller;

use App\Entity\Bestelling;
use App\Form\BestellingType;
use App\Repository\BestellingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/bestelling')]
class BestellingController extends AbstractController
{
    #[Route('/new', name: 'app_bestelling_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Alleen ingelogde gebruikers kunnen bestellen
        $bestelling = new Bestelling();
        $form = $this->createForm(BestellingType::class, $bestelling);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Datum wordt automatisch ingesteld in de constructor van Bestelling
            $entityManager->persist($bestelling);
            $entityManager->flush();

            $this->addFlash('success', 'Je bestelling is succesvol geplaatst!');

            return $this->redirectToRoute('app_product_index');
        }

        return $this->render('bestelling/new.html.twig', [
            'bestelling' => $bestelling,
            'form' => $form->createView(),
        ]);
        
        // TODO: Twig template aanmaken voor bestelformulier
        // - Klantnaam veld
        // - Checkboxes voor alle beschikbare producten
        // - Totaalprijs berekening (JavaScript)
        // - Validatie errors weergeven
        // - Cancel knop terug naar producten
    }

    #[Route('/admin', name: 'app_bestelling_admin', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function admin(BestellingRepository $bestellingRepository): Response
    {
        // Alleen admins kunnen alle bestellingen bekijken
        return $this->render('bestelling/admin.html.twig', [
            'bestellingen' => $bestellingRepository->findBy([], ['datum' => 'DESC']),
        ]);
        
        // TODO: Twig template aanmaken voor bestellingen overzicht
        // - Tabel met alle bestellingen
        // - Klantnaam, datum, aantal producten
        // - Uitklapbare details per bestelling
        // - Producten per bestelling weergeven
        // - Zoek/filter functionaliteit op datum/klant
    }

    #[Route('/{id}', name: 'app_bestelling_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function show(Bestelling $bestelling): Response
    {
        // Detailpagina van een bestelling (alleen admins)
        return $this->render('bestelling/show.html.twig', [
            'bestelling' => $bestelling,
        ]);
        
        // TODO: Twig template aanmaken voor bestelling detail
        // - Alle bestelling informatie
        // - Lijst van alle bestelde producten met prijzen
        // - Totaalprijs berekening
        // - Terug naar admin overzicht knop
    }

    #[Route('/{id}/delete', name: 'app_bestelling_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Bestelling $bestelling, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bestelling->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($bestelling);
            $entityManager->flush();

            $this->addFlash('success', 'Bestelling succesvol verwijderd!');
        }

        return $this->redirectToRoute('app_bestelling_admin');
    }
} 