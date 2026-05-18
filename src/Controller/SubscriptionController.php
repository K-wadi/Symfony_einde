<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Form\SubscriptionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Aanmelden voor het knipabonnement.
class SubscriptionController extends AbstractController
{
    #[Route('/abonnement', name: 'kapsalon_subscription')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $subscription = new Subscription();
        $form = $this->createForm(SubscriptionFormType::class, $subscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subscription->setPriceCents($subscription->getPlanName() === 'Premium' ? 19900 : 9900);
            $subscription->setVisitsIncluded($subscription->getPlanName() === 'Premium' ? 8 : 4);
            $em->persist($subscription);
            $em->flush();
            $request->getSession()->set('customer_email', $subscription->getCustomerEmail());
            $this->addFlash('success', 'Knipabonnement afgesloten!');

            return $this->redirectToRoute('kapsalon_subscription');
        }

        return $this->render('kapsalon/subscription/index.html.twig', [
            'form' => $form,
        ]);
    }
}
