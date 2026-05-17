<?php

namespace App\Repository;

use App\Entity\Smartphone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Smartphone>
 */
class SmartphoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Smartphone::class);
    }

    /**
     * READ: findAll uit presentatie CRUD - alle rijen ophalen.
     *
     * @return Smartphone[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.vendor', 'v')
            ->addSelect('v')
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
