<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentBookingType;
use App\Repository\AppointmentRepository;
use App\Repository\EmployeeRepository;
use App\Repository\TreatmentRepository;
use App\Service\AvailabilityService;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Reserveren via pagina of pop-up; klant beheert afspraak via token-link.
class AppointmentController extends AbstractController
{
    #[Route('/afspraak/reserveren', name: 'kapsalon_appointment_book')]
    public function book(Request $request, TreatmentRepository $treatmentRepository, EmployeeRepository $employeeRepository, AvailabilityService $availability, EntityManagerInterface $em, NotificationService $notifications): Response
    {
        $result = $this->handleBooking($request, $treatmentRepository, $employeeRepository, $availability, $em, $notifications);

        if (isset($result['redirect'])) {
            return $result['redirect'];
        }

        return $this->render('kapsalon/appointment/book.html.twig', [
            'form' => $result['form'],
            'treatments' => $treatmentRepository->findAll(),
        ]);
    }

    public function bookModal(Request $request, TreatmentRepository $treatmentRepository, EmployeeRepository $employeeRepository, AvailabilityService $availability, EntityManagerInterface $em, NotificationService $notifications): Response
    {
        $result = $this->handleBooking($request, $treatmentRepository, $employeeRepository, $availability, $em, $notifications);

        if (isset($result['redirect'])) {
            return $result['redirect'];
        }

        return $this->render('kapsalon/appointment/_modal_form.html.twig', [
            'form' => $result['form'],
        ]);
    }

    // Zelfde logica voor reserveringspagina en modal; bij succes optioneel redirect.
    private function handleBooking(
        Request $request,
        TreatmentRepository $treatmentRepository,
        EmployeeRepository $employeeRepository,
        AvailabilityService $availability,
        EntityManagerInterface $em,
        NotificationService $notifications,
    ): array {
        $slotChoices = [];
        $slotsRequested = false;
        $formData = $request->request->all('appointment_booking');

        if ($request->isMethod('POST') && isset($formData['date'])) {
            $treatment = isset($formData['treatment']) ? $treatmentRepository->find($formData['treatment']) : null;
            $employee = isset($formData['employee']) ? $employeeRepository->find($formData['employee']) : null;
            $dateStr = $formData['date'] ?? null;
            if ($treatment && $employee && $dateStr) {
                $slotsRequested = true;
                $day = new \DateTimeImmutable($dateStr);
                $slotChoices = $availability->getAvailableSlots($day, $employee, $treatment);
                if ($slotChoices === []) {
                    $this->addFlash('warning', 'Alle kappers zijn bezet op deze dag. Kies een andere datum.');
                }
            }
        }

        $form = $this->createForm(AppointmentBookingType::class, null, [
            'slot_choices' => $slotChoices,
            'slots_requested' => $slotsRequested,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slot = $form->get('slot')->getData();
            if (!$slot) {
                $this->addFlash('danger', 'Geen tijd beschikbaar — alle kappers zijn bezet.');

                return ['form' => $form->createView()];
            }

            [$employeeId, $datetime] = explode('|', $slot, 2);
            $employee = $employeeRepository->find((int) $employeeId);
            $treatment = $form->get('treatment')->getData();

            $appointment = new Appointment();
            $appointment->setCustomerName($form->get('customerName')->getData());
            $appointment->setCustomerEmail($form->get('customerEmail')->getData());
            $appointment->setCustomerPhone($form->get('customerPhone')->getData());
            $appointment->setTreatment($treatment);
            $appointment->setEmployee($employee);
            $appointment->setStartsAt(new \DateTimeImmutable($datetime));
            $appointment->setManageToken(bin2hex(random_bytes(16)));

            $em->persist($appointment);
            $em->flush();

            $notifications->sendAppointmentConfirmation($appointment);
            $request->getSession()->set('customer_email', $appointment->getCustomerEmail());
            $this->addFlash('success', 'Afspraak gereserveerd! Bevestiging per e-mail en SMS.');

            return [
                'form' => $form->createView(),
                'redirect' => $this->redirectToRoute('app_home'),
            ];
        }

        return ['form' => $form->createView()];
    }

    #[Route('/afspraak/beheer/{token}', name: 'kapsalon_appointment_manage')]
    public function manage(string $token, Request $request, AppointmentRepository $repository, EntityManagerInterface $em, NotificationService $notifications): Response
    {
        $appointment = $repository->findOneByToken($token);
        if (!$appointment) {
            throw $this->createNotFoundException();
        }

        if ($request->isMethod('POST')) {
            $action = $request->request->get('action');
            if ($action === 'cancel') {
                $appointment->setStatus(Appointment::STATUS_CANCELLED);
                $em->flush();
                $this->addFlash('success', 'Afspraak geannuleerd.');

                return $this->redirectToRoute('app_home');
            }
            if ($action === 'reschedule' && $request->request->get('new_datetime')) {
                $appointment->setStartsAt(new \DateTimeImmutable($request->request->get('new_datetime')));
                $em->flush();
                $notifications->sendAppointmentConfirmation($appointment, 'updated');
                $this->addFlash('success', 'Afspraak gewijzigd. Bevestiging per e-mail en SMS.');
            }
        }

        return $this->render('kapsalon/appointment/manage.html.twig', [
            'appointment' => $appointment,
        ]);
    }
}
