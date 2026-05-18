<?php

namespace App\Controller\Admin;

use App\Repository\PageVisitRepository;
use App\Repository\ShopOrderRepository;
use App\Service\OfferRecommendationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/statistieken')]
#[IsGranted('ROLE_OWNER')]
// Admin: verkoop per periode, gedrag en bezoekfrequentie.
class StatsController extends AbstractController
{
    #[Route('', name: 'kapsalon_admin_stats')]
    public function index(
        Request $request,
        ShopOrderRepository $orders,
        PageVisitRepository $visits,
        OfferRecommendationService $recommendations,
    ): Response {
        $dateTo = $request->query->get('dateTo')
            ? new \DateTimeImmutable($request->query->get('dateTo'))
            : new \DateTimeImmutable('tomorrow');
        $dateFrom = $request->query->get('dateFrom')
            ? new \DateTimeImmutable($request->query->get('dateFrom'))
            : new \DateTimeImmutable('-3 months');

        return $this->render('kapsalon/admin/stats.html.twig', [
            'monthly_sales' => $orders->getTotalsBetween($dateFrom, $dateTo),
            'date_from' => $dateFrom->format('Y-m-d'),
            'date_to' => $dateTo->format('Y-m-d'),
            'visit_frequency' => $recommendations->getCustomerActivityReport(),
            'paths' => $visits->countByPath(),
            'visit_count' => $visits->countAll(),
            'suggested_products' => $recommendations->getSuggestedProductsForAdmin(),
            'ga_id' => $this->getParameter('google_analytics_id'),
        ]);
    }
}
