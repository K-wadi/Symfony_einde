<?php

namespace App\Command;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\SchoolGroup;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Minimaal 5 rijen per tabel (opdracht 1.2) voor beide MtM-relaties.
 */
#[AsCommand(name: 'app:seed-relations', description: 'Vult Book/Author en Student/SchoolGroup met testdata')]
class SeedRelationsCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($this->em->getRepository(Book::class)->count([]) > 0) {
            $io->warning('Data bestaat al. Leeg eerst de tabellen of gebruik een schone database.');

            return Command::SUCCESS;
        }

        // --- Book & Author (relatie 1) ---
        $authors = [
            (new Author())->setName('J.K. Rowling')->setCountry('UK'),
            (new Author())->setName('J.R.R. Tolkien')->setCountry('UK'),
            (new Author())->setName('George R.R. Martin')->setCountry('USA'),
            (new Author())->setName('Stephen King')->setCountry('USA'),
            (new Author())->setName('Lois Lowry')->setCountry('USA'),
        ];
        foreach ($authors as $author) {
            $this->em->persist($author);
        }

        $books = [
            ['Harry Potter 1', '978-0'],
            ['The Hobbit', '978-1'],
            ['A Game of Thrones', '978-2'],
            ['The Shining', '978-3'],
            ['The Giver', '978-4'],
        ];
        $bookEntities = [];
        foreach ($books as [$title, $isbn]) {
            $book = (new Book())->setTitle($title)->setIsbn($isbn);
            $this->em->persist($book);
            $bookEntities[] = $book;
        }

        $bookEntities[0]->addAuthor($authors[0]);
        $bookEntities[1]->addAuthor($authors[1]);
        $bookEntities[2]->addAuthor($authors[2]);
        $bookEntities[2]->addAuthor($authors[0]);
        $bookEntities[3]->addAuthor($authors[3]);
        $bookEntities[4]->addAuthor($authors[4]);

        // --- Student & SchoolGroup (relatie 2) ---
        $groups = [
            (new SchoolGroup())->setName('Klas 1B')->setCode('1B'),
            (new SchoolGroup())->setName('KDL-4')->setCode('KDL4'),
            (new SchoolGroup())->setName('KDL-3')->setCode('KDL3'),
            (new SchoolGroup())->setName('Klas 2A')->setCode('2A'),
            (new SchoolGroup())->setName('Klas 3C')->setCode('3C'),
        ];
        foreach ($groups as $group) {
            $this->em->persist($group);
        }

        $students = [
            (new Student())->setName('Jan')->setEmail('jan@school.nl'),
            (new Student())->setName('Piet')->setEmail('piet@school.nl'),
            (new Student())->setName('Klaas')->setEmail('klaas@school.nl'),
            (new Student())->setName('Sanne')->setEmail('sanne@school.nl'),
            (new Student())->setName('Emma')->setEmail('emma@school.nl'),
        ];
        foreach ($students as $student) {
            $this->em->persist($student);
        }

        // Voorbeeld uit opdracht PDF
        $students[0]->addGroup($groups[0]);
        $students[0]->addGroup($groups[1]);
        $students[1]->addGroup($groups[0]);
        $students[1]->addGroup($groups[2]);
        $students[2]->addGroup($groups[3]);
        $students[3]->addGroup($groups[1]);
        $students[3]->addGroup($groups[4]);
        $students[4]->addGroup($groups[4]);

        $this->em->flush();

        $io->success('Seed klaar: 5+ books, authors, students, groups + koppeltabellen.');

        return Command::SUCCESS;
    }
}
