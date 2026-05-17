<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * USER-overzicht: boeken met gekoppelde auteur (opdracht 4).
     *
     * @return Book[]
     */
    public function findAllWithAuthor(): array
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.author', 'a')
            ->addSelect('a')
            ->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
