<?php

namespace App\Controller;

use App\Entity\Smartphone;
use App\Form\SmartphoneType;
use App\Repository\SmartphoneRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * CRUD controller voor smartphones (opdracht PDF + presentaties create/update/delete).
 */
#[Route('/smartphone')]
class SmartphoneController extends AbstractController
{
    public function __construct(
        private readonly FileUploader $fileUploader,
    ) {
    }

    /**
     * READ: overzicht van alle smartphones.
     * Kolom 'nr' = loop.index in Twig (geen database-id).
     */
    #[Route('/', name: 'app_smartphone_index', methods: ['GET'])]
    public function index(SmartphoneRepository $smartphoneRepository): Response
    {
        return $this->render('smartphone/index.html.twig', [
            'smartphones' => $smartphoneRepository->findAllOrdered(),
        ]);
    }

    /**
     * DETAILS: één smartphone tonen (find uit presentatie CRUD).
     */
    #[Route('/{id}', name: 'app_smartphone_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Smartphone $smartphone): Response
    {
        return $this->render('smartphone/show.html.twig', [
            'smartphone' => $smartphone,
        ]);
    }

    /**
     * CREATE: nieuwe smartphone + flashmessage (opdracht PDF).
     */
    #[Route('/new', name: 'app_smartphone_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $smartphone = new Smartphone();
        $form = $this->createForm(SmartphoneType::class, $smartphone, [
            'require_picture' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handlePictureUpload($form->get('pictureFile')->getData(), $smartphone);

            // persist + flush uit presentatie create
            $entityManager->persist($smartphone);
            $entityManager->flush();

            $this->addFlash('success', 'Smartphone is toegevoegd.');

            return $this->redirectToRoute('app_smartphone_index');
        }

        return $this->render('smartphone/new.html.twig', [
            'smartphone' => $smartphone,
            'form' => $form,
        ]);
    }

    /**
     * UPDATE: bestaande smartphone wijzigen + flashmessage.
     */
    #[Route('/{id}/edit', name: 'app_smartphone_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Smartphone $smartphone, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SmartphoneType::class, $smartphone, [
            'require_picture' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $pictureFile */
            $pictureFile = $form->get('pictureFile')->getData();

            if ($pictureFile) {
                $this->fileUploader->remove($smartphone->getPicture());
                $this->handlePictureUpload($pictureFile, $smartphone);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Smartphone is bijgewerkt.');

            return $this->redirectToRoute('app_smartphone_index');
        }

        return $this->render('smartphone/edit.html.twig', [
            'smartphone' => $smartphone,
            'form' => $form,
        ]);
    }

    /**
     * DELETE: eerst details tonen, daarna pas verwijderen (opdracht PDF).
     * remove + flush uit presentatie delete.
     */
    #[Route('/{id}/delete', name: 'app_smartphone_delete', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function delete(Request $request, Smartphone $smartphone, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            if ($this->isCsrfTokenValid('delete'.$smartphone->getId(), $request->request->get('_token'))) {
                $this->fileUploader->remove($smartphone->getPicture());
                $entityManager->remove($smartphone);
                $entityManager->flush();

                $this->addFlash('success', 'Smartphone is verwijderd.');

                return $this->redirectToRoute('app_smartphone_index');
            }
        }

        return $this->render('smartphone/delete.html.twig', [
            'smartphone' => $smartphone,
        ]);
    }

    private function handlePictureUpload(?UploadedFile $pictureFile, Smartphone $smartphone): void
    {
        if ($pictureFile) {
            $fileName = $this->fileUploader->upload($pictureFile);
            $smartphone->setPicture($fileName);
        }
    }
}
