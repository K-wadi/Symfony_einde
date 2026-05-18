<?php

namespace App\Repository;

use App\Entity\ShopOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<ShopOrder> */
class ShopOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShopOrder::class);
    }

    // Omzet per maand voor het dashboard (laatste 12 maanden).
    public function getMonthlyTotals(): array
    {
        return $this->getTotalsBetween(
            new \DateTimeImmutable('-12 months'),
            new \DateTimeImmutable('tomorrow')
        );
    }

    // Omzet per maand binnen gekozen datumbereik (voor grafiek).
    public function getTotalsBetween(\DateTimeInterface $from, \DateTimeInterface $to): array
    {
        $orders = $this->createQueryBuilder('o')
            ->andWhere('o.createdAt >= :from')
            ->andWhere('o.createdAt < :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->orderBy('o.createdAt', 'ASC')
            ->getQuery()
            ->getResult();

        $totals = [];
        foreach ($orders as $order) {
            $key = $order->getCreatedAt()?->format('Y-m') ?? 'unknown';
            $totals[$key] = ($totals[$key] ?? 0) + $order->getTotalCents();
        }

        return $totals;
    }

    // Populairste producten bij klanten met minstens 2 bestellingen.
    public function getTopProductIdsForRepeatCustomers(int $limit = 5): array
    {
        $emails = $this->createQueryBuilder('o')
            ->select('o.customerEmail')
            ->groupBy('o.customerEmail')
            ->having('COUNT(o.id) >= 2')
            ->getQuery()
            ->getSingleColumnResult();

        if ($emails === []) {
            return [];
        }

        $rows = $this->getEntityManager()->createQuery(
            'SELECT IDENTITY(l.product) AS pid, COUNT(l.id) AS cnt
             FROM App\Entity\ShopOrderLine l
             JOIN l.shopOrder o
             WHERE o.customerEmail IN (:emails)
             GROUP BY pid
             ORDER BY cnt DESC'
        )
            ->setParameter('emails', $emails)
            ->setMaxResults($limit)
            ->getArrayResult();

        return array_map(fn (array $r) => (int) $r['pid'], $rows);
    }
}
