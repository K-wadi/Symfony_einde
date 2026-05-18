<?php

namespace App\Repository;

use App\Entity\PageVisit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<PageVisit> */
class PageVisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageVisit::class);
    }

    // Totaal aantal paginaweergaven op de site.
    public function countAll(): int
    {
        return (int) $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Per URL-pad hoe vaak bezocht.
    public function countByPath(): array
    {
        $rows = $this->createQueryBuilder('p')
            ->select('p.path AS path, COUNT(p.id) AS cnt')
            ->groupBy('p.path')
            ->getQuery()
            ->getArrayResult();

        $result = [];
        foreach ($rows as $row) {
            $result[$row['path']] = (int) $row['cnt'];
        }

        return $result;
    }
}
