<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Boeken en auteurs (opdracht 4).
 */
class BookController extends AbstractController
{
    /**
     * ROLE_USER: overzicht boeken met auteur.
     */
    #[Route('/books', name: 'app_book_user_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function userIndex(BookRepository $bookRepository): Response
    {
        return $this->render('book/user_index.html.twig', [
            'books' => $bookRepository->findAllWithAuthor(),
        ]);
    }

    #[Route('/admin/books', name: 'app_admin_book_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function adminBookIndex(BookRepository $bookRepository): Response
    {
        return $this->render('book/admin_books.html.twig', [
            'books' => $bookRepository->findAllWithAuthor(),
        ]);
    }

    #[Route('/admin/books/delete/{id}', name: 'app_admin_book_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteBook(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_book'.$book->getId(), $request->request->get('_token'))) {
            $entityManager->remove($book);
            $entityManager->flush();
            $this->addFlash('success', 'Boek verwijderd.');
        }

        return $this->redirectToRoute('app_admin_book_index');
    }

    #[Route('/admin/authors', name: 'app_admin_author_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function adminAuthorIndex(AuthorRepository $authorRepository): Response
    {
        return $this->render('book/admin_authors.html.twig', [
            'authors' => $authorRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    #[Route('/admin/authors/delete/{id}', name: 'app_admin_author_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteAuthor(Request $request, Author $author, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_author'.$author->getId(), $request->request->get('_token'))) {
            $entityManager->remove($author);
            $entityManager->flush();
            $this->addFlash('success', 'Auteur verwijderd.');
        }

        return $this->redirectToRoute('app_admin_author_index');
    }
}
