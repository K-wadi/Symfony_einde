<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Toont de algemene voorwaarden van de kapsalon.
class TermsController extends AbstractController
{
    #[Route('/algemene-voorwaarden', name: 'kapsalon_terms')]
    public function index(): Response
    {
        return $this->render('kapsalon/terms/index.html.twig');
    }
}
