<?php

namespace App\Service;

use App\Entity\Offer;
use App\Entity\SalonProduct;
use App\Repository\AppointmentRepository;
use App\Repository\OfferRepository;
use App\Repository\SalonProductRepository;
use App\Repository\ShopOrderRepository;

// Bepaalt aanbiedingen en statistieken op basis van klantgedrag (bestellingen en afspraken).
class OfferRecommendationService
{
    public function __construct(
        private readonly ShopOrderRepository $shopOrderRepository,
        private readonly OfferRepository $offerRepository,
        private readonly SalonProductRepository $salonProductRepository,
        private readonly AppointmentRepository $appointmentRepository,
    ) {
    }

    // Meer bestellingen = meer persoonlijke aanbiedingen op de homepagina.
    public function getOffersForVisitor(?string $customerEmail): array
    {
        $offers = $this->offerRepository->findActive();
        if ($customerEmail === null) {
            return array_slice($offers, 0, 1);
        }

        $orderCount = count($this->shopOrderRepository->findBy(['customerEmail' => $customerEmail]));
        if ($orderCount >= 3) {
            return $offers;
        }
        if ($orderCount >= 1) {
            return array_slice($offers, 0, min(2, count($offers)));
        }

        return array_slice($offers, 0, 1);
    }

    // Producten die vaak gekocht worden door klanten met 2+ bestellingen.
    public function getSuggestedProductsForAdmin(): array
    {
        $ids = $this->shopOrderRepository->getTopProductIdsForRepeatCustomers(5);
        $products = [];
        foreach ($ids as $id) {
            $product = $this->salonProductRepository->find($id);
            if ($product) {
                $products[] = $product;
            }
        }

        if ($products !== []) {
            return $products;
        }

        return $this->salonProductRepository->findBy([], ['name' => 'ASC'], 5);
    }

    // Telt per e-mail bestellingen en afspraken voor het admin-overzicht.
    public function getCustomerActivityReport(): array
    {
        $report = [];
        foreach ($this->shopOrderRepository->findAll() as $order) {
            $email = $order->getCustomerEmail();
            if (!$email) {
                continue;
            }
            $report[$email] ??= ['orders' => 0, 'appointments' => 0, 'total' => 0];
            ++$report[$email]['orders'];
        }
        foreach ($this->appointmentRepository->countByCustomerEmail() as $email => $cnt) {
            $report[$email] ??= ['orders' => 0, 'appointments' => 0, 'total' => 0];
            $report[$email]['appointments'] = $cnt;
        }
        foreach ($report as $email => $data) {
            $report[$email]['total'] = $data['orders'] + $data['appointments'];
        }
        uasort($report, fn ($a, $b) => $b['total'] <=> $a['total']);

        return $report;
    }
}
