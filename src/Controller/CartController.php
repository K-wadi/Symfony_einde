<?php

namespace App\Controller;

use App\Entity\ShopOrder;
use App\Entity\ShopOrderLine;
use App\Form\CheckoutType;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Winkelwagen en afrekenen; slaat klant-e-mail op in sessie voor aanbiedingen.
class CartController extends AbstractController
{
    #[Route('/winkelwagen', name: 'kapsalon_cart')]
    public function index(CartSessionStorage $cart): Response
    {
        return $this->render('kapsalon/cart/index.html.twig', [
            'lines' => $cart->getLines(),
            'total_cents' => $cart->getTotalCents(),
            'count' => $cart->getNumberOfProducts(),
        ]);
    }

    #[Route('/winkelwagen/afrekenen', name: 'kapsalon_checkout')]
    public function checkout(Request $request, CartSessionStorage $cart, EntityManagerInterface $em): Response
    {
        if ($cart->getNumberOfProducts() === 0) {
            $this->addFlash('warning', 'Winkelwagen is leeg.');

            return $this->redirectToRoute('kapsalon_products');
        }

        $order = new ShopOrder();
        $form = $this->createForm(CheckoutType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($cart->getLines() as $line) {
                $orderLine = new ShopOrderLine();
                $orderLine->setProduct($line->getProduct());
                $orderLine->setQuantity($line->getQuantity());
                $order->addLine($orderLine);
            }
            $em->persist($order);
            $em->flush();
            $request->getSession()->set('customer_email', $order->getCustomerEmail());
            $cart->clear();
            $this->addFlash('success', 'Bestelling geplaatst!');

            return $this->redirectToRoute('kapsalon_cart');
        }

        return $this->render('kapsalon/cart/checkout.html.twig', [
            'form' => $form,
            'total_cents' => $cart->getTotalCents(),
        ]);
    }
}
