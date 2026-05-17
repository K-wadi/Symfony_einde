<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderLine;
use App\Form\CheckoutType;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Winkelwagen (stap 13–16 + checkout).
 */
#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart')]
    public function index(Request $request, CartSessionStorage $cartStorage, EntityManagerInterface $em): Response
    {
        $checkoutForm = $this->createForm(CheckoutType::class);
        $checkoutForm->handleRequest($request);

        if ($checkoutForm->isSubmitted() && $checkoutForm->isValid()) {
            $cart = $cartStorage->getShoppingCart();
            if (empty($cart)) {
                $this->addFlash('warning', 'Je winkelwagen is leeg.');

                return $this->redirectToRoute('app_cart');
            }

            $data = $checkoutForm->getData();
            $order = (new Order())
                ->setCustomerName($data['customerName'])
                ->setEmail($data['email']);

            foreach ($cart as $sessionLine) {
                $line = new OrderLine();
                $line->setProduct($sessionLine->getProduct());
                $line->setQuantity($sessionLine->getQuantity());
                $order->addOrderLine($line);
                $em->persist($line);
            }

            $em->persist($order);
            $em->flush();

            $cartStorage->clearShoppingCart();
            $this->addFlash('success', 'Bedankt! Je bestelling is opgeslagen.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('cart/index.html.twig', [
            'shoppingCart' => $cartStorage->getShoppingCart() ?? [],
            'totalPrice' => $cartStorage->getTotalPrice(),
            'checkoutForm' => $checkoutForm,
        ]);
    }

    #[Route('/clear', name: 'app_cart_clear', methods: ['POST'])]
    public function clear(Request $request, CartSessionStorage $cartStorage): Response
    {
        if ($this->isCsrfTokenValid('clear_cart', $request->request->get('_token'))) {
            $cartStorage->clearShoppingCart();
            $this->addFlash('success', 'Winkelwagen geleegd.');
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/increase/{id}', name: 'app_cart_increase', requirements: ['id' => '\d+'])]
    public function increase(int $id, CartSessionStorage $cartStorage): Response
    {
        $cartStorage->increaseQuantity($id);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/decrease/{id}', name: 'app_cart_decrease', requirements: ['id' => '\d+'])]
    public function decrease(int $id, CartSessionStorage $cartStorage): Response
    {
        $cartStorage->decreaseQuantity($id);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/remove/{id}', name: 'app_cart_remove', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function remove(int $id, Request $request, CartSessionStorage $cartStorage): Response
    {
        if ($this->isCsrfTokenValid('remove'.$id, $request->request->get('_token'))) {
            $cartStorage->removeProductFromCart($id);
        }

        return $this->redirectToRoute('app_cart');
    }
}
