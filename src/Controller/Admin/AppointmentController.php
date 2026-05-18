<?php

namespace App\Controller\Admin;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use App\Repository\EmployeeRepository;
use App\Repository\TreatmentRepository;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/afspraken')]
#[IsGranted('ROLE_OWNER')]
// Admin: afspraken bekijken, aanmaken en beheren.
class AppointmentController extends AbstractController
{
    #[Route('', name: 'kapsalon_admin_appointments')]
    public function index(AppointmentRepository $repository): Response
    {
        return $this->render('kapsalon/admin/appointment/index.html.twig', [
            'appointments' => $repository->findBy([], ['startsAt' => 'DESC']),
        ]);
    }

    #[Route('/nieuw', name: 'kapsalon_admin_appointment_new')]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        EmployeeRepository $employees,
        TreatmentRepository $treatments,
        NotificationService $notifications,
    ): Response {
        if ($request->isMethod('POST')) {
            $appointment = new Appointment();
            $appointment->setCustomerName($request->request->get('customerName', ''));
            $appointment->setCustomerEmail($request->request->get('customerEmail', ''));
            $appointment->setCustomerPhone($request->request->get('customerPhone'));
            $appointment->setEmployee($employees->find($request->request->get('employee')));
            $appointment->setTreatment($treatments->find($request->request->get('treatment')));
            $appointment->setStartsAt(new \DateTimeImmutable($request->request->get('startsAt')));
            $appointment->setManageToken(bin2hex(random_bytes(16)));
            $em->persist($appointment);
            $em->flush();
            $notifications->sendAppointmentConfirmation($appointment);
            $this->addFlash('success', 'Afspraak gepland door kapsalon.');

            return $this->redirectToRoute('kapsalon_admin_appointments');
        }

        return $this->render('kapsalon/admin/appointment/new.html.twig', [
            'employees' => $employees->findAll(),
            'treatments' => $treatments->findAll(),
        ]);
    }

    #[Route('/{id}/bewerken', name: 'kapsalon_admin_appointment_edit')]
    public function edit(Appointment $appointment, Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $appointment->setCustomerName($request->request->get('customerName', $appointment->getCustomerName()));
            $appointment->setStartsAt(new \DateTimeImmutable($request->request->get('startsAt', $appointment->getStartsAt()->format('Y-m-d H:i'))));
            $em->flush();
            $this->addFlash('success', 'Afspraak bijgewerkt.');

            return $this->redirectToRoute('kapsalon_admin_appointments');
        }

        return $this->render('kapsalon/admin/appointment/edit.html.twig', ['appointment' => $appointment]);
    }

    #[Route('/{id}/annuleren', name: 'kapsalon_admin_appointment_cancel', methods: ['POST'])]
    public function cancel(Appointment $appointment, EntityManagerInterface $em): Response
    {
        $appointment->setStatus(Appointment::STATUS_CANCELLED);
        $em->flush();
        $this->addFlash('success', 'Afspraak geannuleerd.');

        return $this->redirectToRoute('kapsalon_admin_appointments');
    }

    #[Route('/{id}/verwijderen', name: 'kapsalon_admin_appointment_delete', methods: ['POST'])]
    public function delete(Appointment $appointment, EntityManagerInterface $em): Response
    {
        $em->remove($appointment);
        $em->flush();
        $this->addFlash('success', 'Afspraak verwijderd.');

        return $this->redirectToRoute('kapsalon_admin_appointments');
    }
}
