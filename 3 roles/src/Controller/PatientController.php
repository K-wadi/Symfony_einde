<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\PatientProfileType;
use App\Repository\AppointmentRepository;
use App\Repository\NewsRepository;
use App\Repository\PatientNotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Patiënt homepage, profiel, afspraken, nieuws (opdracht stap 5).
 */
#[Route('/patient')]
#[IsGranted('ROLE_PATIENT')]
class PatientController extends AbstractController
{
    #[Route('/', name: 'app_patient_home')]
    public function home(
        NewsRepository $newsRepository,
        PatientNotificationRepository $notificationRepository,
    ): Response {
        /** @var Patient $patient */
        $patient = $this->getUser();

        return $this->render('patient/home.html.twig', [
            'newsItems' => $newsRepository->findLatest(),
            'notifications' => $notificationRepository->findUnreadForPatient($patient),
        ]);
    }

    #[Route('/appointments', name: 'app_patient_appointments')]
    public function appointments(AppointmentRepository $appointmentRepository): Response
    {
        /** @var Patient $patient */
        $patient = $this->getUser();

        return $this->render('patient/appointments.html.twig', [
            'appointments' => $appointmentRepository->findForPatient($patient),
        ]);
    }

    #[Route('/profile', name: 'app_patient_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var Patient $patient */
        $patient = $this->getUser();
        $form = $this->createForm(PatientProfileType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Profiel opgeslagen.');

            return $this->redirectToRoute('app_patient_profile');
        }

        return $this->render('patient/profile.html.twig', [
            'form' => $form,
        ]);
    }
}
