<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Homepage bezoeker (opdracht stap 3).
 */
class GuestController extends AbstractController
{
    #[Route('/guest', name: 'app_guest_home')]
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('guest/index.html.twig');
    }
}
