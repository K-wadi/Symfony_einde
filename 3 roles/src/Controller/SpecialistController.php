<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Specialist;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use App\Service\PatientNotifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Specialist homepage + afspraken CRUD (opdracht stap 6 en 7).
 */
#[Route('/specialist')]
#[IsGranted('ROLE_SPECIALIST')]
class SpecialistController extends AbstractController
{
    #[Route('/', name: 'app_specialist_home')]
    public function home(AppointmentRepository $appointmentRepository): Response
    {
        /** @var Specialist $specialist */
        $specialist = $this->getUser();

        return $this->render('specialist/home.html.twig', [
            'upcoming' => array_slice($appointmentRepository->findForSpecialist($specialist), 0, 5),
        ]);
    }

    #[Route('/appointments', name: 'app_specialist_appointments')]
    public function appointments(AppointmentRepository $appointmentRepository): Response
    {
        /** @var Specialist $specialist */
        $specialist = $this->getUser();

        return $this->render('specialist/appointments/index.html.twig', [
            'appointments' => $appointmentRepository->findForSpecialist($specialist),
        ]);
    }

    #[Route('/appointments/new', name: 'app_specialist_appointment_new')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        /** @var Specialist $specialist */
        $specialist = $this->getUser();

        $appointment = new Appointment();
        $appointment->setSpecialist($specialist);

        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($appointment);
            $entityManager->flush();

            $this->addFlash('success', 'Afspraak aangemaakt.');

            return $this->redirectToRoute('app_specialist_appointments');
        }

        return $this->render('specialist/appointments/form.html.twig', [
            'form' => $form,
            'title' => 'Nieuwe afspraak',
        ]);
    }

    #[Route('/appointments/{id}/edit', name: 'app_specialist_appointment_edit', requirements: ['id' => '\d+'])]
    public function edit(
        Request $request,
        Appointment $appointment,
        EntityManagerInterface $entityManager,
        PatientNotifier $patientNotifier,
    ): Response {
        $this->denyAccessUnlessGrantedToOwnAppointment($appointment);

        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $patientNotifier->notifyAppointmentChanged($appointment, 'aangepast');
            $entityManager->flush();

            $this->addFlash('success', 'Afspraak bijgewerkt. De patiënt ontvangt een bericht.');

            return $this->redirectToRoute('app_specialist_appointments');
        }

        return $this->render('specialist/appointments/form.html.twig', [
            'form' => $form,
            'title' => 'Afspraak bewerken',
        ]);
    }

    #[Route('/appointments/{id}/delete', name: 'app_specialist_appointment_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(
        Request $request,
        Appointment $appointment,
        EntityManagerInterface $entityManager,
        PatientNotifier $patientNotifier,
    ): Response {
        $this->denyAccessUnlessGrantedToOwnAppointment($appointment);

        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->request->get('_token'))) {
            $patientNotifier->notifyAppointmentChanged($appointment, 'verwijderd');
            $entityManager->remove($appointment);
            $entityManager->flush();
            $this->addFlash('success', 'Afspraak verwijderd. De patiënt ontvangt een bericht.');
        }

        return $this->redirectToRoute('app_specialist_appointments');
    }

    private function denyAccessUnlessGrantedToOwnAppointment(Appointment $appointment): void
    {
        /** @var Specialist $specialist */
        $specialist = $this->getUser();
        if ($appointment->getSpecialist()?->getId() !== $specialist->getId()) {
            throw $this->createAccessDeniedException();
        }
    }
}
