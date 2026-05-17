<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use App\Repository\PatientRepository;
use App\Repository\SpecialistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Admin: nieuws + overzicht patiënten/specialisten (opdracht rollen admin).
 */
#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_home')]
    public function home(
        PatientRepository $patientRepository,
        SpecialistRepository $specialistRepository,
        NewsRepository $newsRepository,
    ): Response {
        return $this->render('admin/home.html.twig', [
            'patientCount' => count($patientRepository->findAll()),
            'specialistCount' => count($specialistRepository->findAll()),
            'newsCount' => count($newsRepository->findAll()),
        ]);
    }

    #[Route('/users', name: 'app_admin_users')]
    public function users(
        PatientRepository $patientRepository,
        SpecialistRepository $specialistRepository,
    ): Response {
        return $this->render('admin/users.html.twig', [
            'patients' => $patientRepository->findAllOrderedByName(),
            'specialists' => $specialistRepository->findAll(),
        ]);
    }

    #[Route('/news', name: 'app_admin_news')]
    public function news(NewsRepository $newsRepository): Response
    {
        return $this->render('admin/news/index.html.twig', [
            'newsItems' => $newsRepository->findLatest(),
        ]);
    }

    #[Route('/news/new', name: 'app_admin_news_new')]
    public function newNews(Request $request, EntityManagerInterface $entityManager): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $news->setPublishedAt(new \DateTimeImmutable());
            $entityManager->persist($news);
            $entityManager->flush();

            $this->addFlash('success', 'Nieuwsbericht gepubliceerd.');

            return $this->redirectToRoute('app_admin_news');
        }

        return $this->render('admin/news/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/news/{id}/delete', name: 'app_admin_news_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function deleteNews(Request $request, News $news, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_news'.$news->getId(), $request->request->get('_token'))) {
            $entityManager->remove($news);
            $entityManager->flush();
            $this->addFlash('success', 'Nieuwsbericht verwijderd.');
        }

        return $this->redirectToRoute('app_admin_news');
    }
}
