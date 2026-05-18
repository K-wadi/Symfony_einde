<?php

namespace App\Controller;

use App\Repository\TreatmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Publiek overzicht van alle behandelingen.
class TreatmentController extends AbstractController
{
    #[Route('/behandelingen', name: 'kapsalon_treatments')]
    public function index(TreatmentRepository $repository): Response
    {
        return $this->render('kapsalon/treatment/index.html.twig', [
            'treatments' => $repository->findBy([], ['name' => 'ASC']),
        ]);
    }
}
