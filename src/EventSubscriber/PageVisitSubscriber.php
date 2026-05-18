<?php

namespace App\EventSubscriber;

use App\Entity\PageVisit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

// Telt paginaweergaven voor bezoekersstatistiek in admin.
class PageVisitSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly RequestStack $requestStack,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => ['onRequest', -10]];
    }

    public function onRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (str_starts_with($request->getPathInfo(), '/_')) {
            return;
        }

        $visit = new PageVisit();
        $visit->setPath($request->getPathInfo());
        $session = $this->requestStack->getSession();
        $visit->setVisitorKey($session->getId());

        $this->em->persist($visit);
        $this->em->flush();
    }
}
