<?php

namespace App\Controller\Admin;

use App\Entity\Treatment;
use App\Form\TreatmentType;
use App\Repository\TreatmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/behandelingen')]
#[IsGranted('ROLE_OWNER')]
// Admin: behandelingen toevoegen en bewerken.
class TreatmentController extends AbstractController
{
    #[Route('', name: 'kapsalon_admin_treatments')]
    public function index(TreatmentRepository $repository): Response
    {
        return $this->render('kapsalon/admin/treatment/index.html.twig', [
            'treatments' => $repository->findAll(),
        ]);
    }

    #[Route('/nieuw', name: 'kapsalon_admin_treatment_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $treatment = new Treatment();
        $form = $this->createForm(TreatmentType::class, $treatment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $euro = $form->get('priceEuro')->getData();
            $treatment->setPriceCents((int) round((float) $euro * 100));
            $em->persist($treatment);
            $em->flush();
            $this->addFlash('success', 'Behandeling toegevoegd.');

            return $this->redirectToRoute('kapsalon_admin_treatments');
        }

        return $this->render('kapsalon/admin/treatment/form.html.twig', ['form' => $form]);
    }
}
