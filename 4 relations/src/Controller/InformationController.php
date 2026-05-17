<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\SchoolGroupRepository;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Many-to-Many tonen (opdracht 1.3 en 1.4 – showInformation).
 */
class InformationController extends AbstractController
{
    /**
     * Relatie 1: Book ↔ Author (opdracht 1.2 + 1.3).
     */
    #[Route('/show_information/books', name: 'app_show_information_books')]
    public function showInformationBooks(
        BookRepository $bookRepository,
        AuthorRepository $authorRepository,
    ): Response {
        $books = $bookRepository->findAll();
        $authors = $authorRepository->findAll();

        return $this->render('information/books_authors.html.twig', [
            'books' => $books,
            'authors' => $authors,
        ]);
    }

    /**
     * Relatie 2: Student ↔ SchoolGroup (opdracht 1.4).
     */
    #[Route('/show_information/students', name: 'app_show_information_students')]
    public function showInformationStudents(
        StudentRepository $studentRepository,
        SchoolGroupRepository $schoolGroupRepository,
    ): Response {
        $students = $studentRepository->findAll();
        $groups = $schoolGroupRepository->findAll();

        return $this->render('information/students_groups.html.twig', [
            'students' => $students,
            'groups' => $groups,
        ]);
    }

    /**
     * Alias zoals in opdracht: show_information (eerste relatie).
     */
    #[Route('/show_information', name: 'app_show_information')]
    public function showInformation(): Response
    {
        return $this->redirectToRoute('app_show_information_books');
    }
}
