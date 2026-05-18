<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/aanbiedingen')]
#[IsGranted('ROLE_OWNER')]
// Admin: aanbiedingen beheren.
class OfferController extends AbstractController
{
    #[Route('', name: 'kapsalon_admin_offers')]
    public function index(OfferRepository $repository): Response
    {
        return $this->render('kapsalon/admin/offer/index.html.twig', [
            'offers' => $repository->findAll(),
        ]);
    }

    #[Route('/nieuw', name: 'kapsalon_admin_offer_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($offer);
            $em->flush();
            $this->addFlash('success', 'Aanbieding opgeslagen.');

            return $this->redirectToRoute('kapsalon_admin_offers');
        }

        return $this->render('kapsalon/admin/offer/form.html.twig', ['form' => $form]);
    }
}
