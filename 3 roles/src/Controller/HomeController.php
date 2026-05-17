<?php

namespace App\Controller;

use App\Entity\AdminUser;
use App\Entity\Patient;
use App\Entity\Specialist;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Home-knop stuurt door naar de juiste homepage per rol (opdracht stap 5 navigatie).
 */
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_guest_home');
        }

        if ($user instanceof Patient) {
            return $this->redirectToRoute('app_patient_home');
        }

        if ($user instanceof Specialist) {
            return $this->redirectToRoute('app_specialist_home');
        }

        if ($user instanceof AdminUser) {
            return $this->redirectToRoute('app_admin_home');
        }

        return $this->redirectToRoute('app_guest_home');
    }
}
