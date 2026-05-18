<?php

namespace App\Controller\Staff;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/staff')]
#[IsGranted('ROLE_EMPLOYEE')]
// Medewerker: eigen dashboard na inloggen.
class DashboardController extends AbstractController
{
    #[Route('', name: 'kapsalon_staff_dashboard')]
    public function index(): Response
    {
        return $this->render('kapsalon/staff/dashboard.html.twig');
    }
}
