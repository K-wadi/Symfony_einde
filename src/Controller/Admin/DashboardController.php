<?php

namespace App\Controller\Admin;

use App\Repository\AppointmentRepository;
use App\Repository\PageVisitRepository;
use App\Repository\ShopOrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_OWNER')]
// Admin: startpagina met bezoekers en verkopen.
class DashboardController extends AbstractController
{
    #[Route('', name: 'kapsalon_admin_dashboard')]
    public function index(PageVisitRepository $visits, ShopOrderRepository $orders, AppointmentRepository $appointments): Response
    {
        return $this->render('kapsalon/admin/dashboard.html.twig', [
            'visit_count' => $visits->countAll(),
            'monthly_sales' => $orders->getMonthlyTotals(),
            'appointment_count' => count($appointments->findAll()),
        ]);
    }
}
