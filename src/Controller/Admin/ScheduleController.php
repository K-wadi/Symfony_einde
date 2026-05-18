<?php

namespace App\Controller\Admin;

use App\Repository\AppointmentRepository;
use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/rooster')]
#[IsGranted('ROLE_OWNER')]
// Admin: roosteroverzicht van medewerkers.
class ScheduleController extends AbstractController
{
    #[Route('', name: 'kapsalon_admin_schedule')]
    public function index(EmployeeRepository $employees, AppointmentRepository $appointments): Response
    {
        $schedule = [];
        foreach ($employees->findAll() as $employee) {
            $schedule[$employee->getName()] = $appointments->findForEmployee($employee);
        }

        return $this->render('kapsalon/admin/schedule.html.twig', [
            'schedule' => $schedule,
        ]);
    }
}
