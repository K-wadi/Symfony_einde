<?php

namespace App\Controller\Staff;

use App\Entity\Appointment;
use App\Entity\Employee;
use App\Repository\AppointmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/staff/afspraken')]
#[IsGranted('ROLE_EMPLOYEE')]
// Medewerker: eigen afspraken inzien en aanpassen.
class AppointmentController extends AbstractController
{
    private function getEmployee(): ?Employee
    {
        $user = $this->getUser();
        if (!$user || !method_exists($user, 'getEmployee')) {
            return null;
        }

        return $user->getEmployee();
    }

    #[Route('', name: 'kapsalon_staff_appointments')]
    public function index(AppointmentRepository $repository): Response
    {
        $employee = $this->getEmployee();
        $appointments = $employee ? $repository->findForEmployee($employee) : $repository->findAll();

        return $this->render('kapsalon/staff/appointment/index.html.twig', [
            'appointments' => $appointments,
        ]);
    }

    #[Route('/{id}/bewerken', name: 'kapsalon_staff_appointment_edit')]
    public function edit(Appointment $appointment, Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $appointment->setCustomerName($request->request->get('customerName', $appointment->getCustomerName()));
            $appointment->setStartsAt(new \DateTimeImmutable($request->request->get('startsAt', $appointment->getStartsAt()->format('Y-m-d H:i'))));
            $em->flush();
            $this->addFlash('success', 'Afspraak bijgewerkt.');

            return $this->redirectToRoute('kapsalon_staff_appointments');
        }

        return $this->render('kapsalon/staff/appointment/edit.html.twig', ['appointment' => $appointment]);
    }

    #[Route('/{id}/annuleren', name: 'kapsalon_staff_appointment_cancel', methods: ['POST'])]
    public function cancel(Appointment $appointment, EntityManagerInterface $em): Response
    {
        $appointment->setStatus(Appointment::STATUS_CANCELLED);
        $em->flush();
        $this->addFlash('success', 'Afspraak geannuleerd.');

        return $this->redirectToRoute('kapsalon_staff_appointments');
    }
}
