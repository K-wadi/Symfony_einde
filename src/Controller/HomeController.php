<?php

namespace App\Controller;

use App\Repository\OfferRepository;
use App\Repository\SalonProductRepository;
use App\Repository\TreatmentRepository;
use App\Service\OfferRecommendationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Homepagina met visie, aanbiedingen en persoonlijke aanbevelingen.
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        OfferRepository $offerRepository,
        TreatmentRepository $treatmentRepository,
        SalonProductRepository $productRepository,
        OfferRecommendationService $recommendations,
        Request $request,
    ): Response {
        $email = $request->getSession()->get('customer_email');
        $personalOffers = $recommendations->getOffersForVisitor(is_string($email) ? $email : null);

        return $this->render('kapsalon/home/index.html.twig', [
            'offers' => $offerRepository->findActive(),
            'personal_offers' => $personalOffers,
            'treatments' => $treatmentRepository->findBy([], ['name' => 'ASC'], 4),
            'products' => $productRepository->findBy([], ['name' => 'ASC'], 4),
        ]);
    }
}
