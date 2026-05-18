<?php

namespace App\Controller\Admin;

use App\Entity\TimeBlock;
use App\Form\TimeBlockType;
use App\Repository\TimeBlockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/tijdsblokken')]
#[IsGranted('ROLE_OWNER')]
// Admin: werkuren en tijdsblokken per kapster.
class TimeBlockController extends AbstractController
{
    #[Route('', name: 'kapsalon_admin_timeblocks')]
    public function index(TimeBlockRepository $repository): Response
    {
        return $this->render('kapsalon/admin/timeblock/index.html.twig', [
            'blocks' => $repository->findAll(),
        ]);
    }

    #[Route('/nieuw', name: 'kapsalon_admin_timeblock_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $block = new TimeBlock();
        $form = $this->createForm(TimeBlockType::class, $block);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($block);
            $em->flush();
            $this->addFlash('success', 'Tijdsblok opgeslagen.');

            return $this->redirectToRoute('kapsalon_admin_timeblocks');
        }

        return $this->render('kapsalon/admin/timeblock/form.html.twig', ['form' => $form]);
    }
}
