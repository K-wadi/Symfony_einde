<?php

namespace App\Controller;

use App\Entity\Pizza;
use App\Form\PizzaType;
use App\Repository\PizzaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Formulier opdracht 1.1–1.4: eerst form(form), daarna form_rows template.
 */
#[Route('/pizza')]
class PizzaController extends AbstractController
{
    #[Route('/', name: 'app_pizza_index')]
    public function index(PizzaRepository $pizzaRepository): Response
    {
        return $this->render('pizza/index.html.twig', [
            'pizzas' => $pizzaRepository->findAll(),
        ]);
    }

    /** Opdracht 1.1: {{ form(form) }} variant */
    #[Route('/new/simple', name: 'app_pizza_new_simple')]
    public function newSimple(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pizza = new Pizza();
        $form = $this->createForm(PizzaType::class, $pizza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pizza);
            $entityManager->flush();
            $this->addFlash('success', 'Pizza opgeslagen (simple form).');

            return $this->redirectToRoute('app_pizza_index');
        }

        return $this->render('pizza/new_simple.html.twig', [
            'form' => $form,
        ]);
    }

    /** Opdracht 1.3 + 1.4: form_rows + help/label/data op velden */
    #[Route('/new', name: 'app_pizza_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pizza = new Pizza();
        $form = $this->createForm(PizzaType::class, $pizza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pizza);
            $entityManager->flush();
            $this->addFlash('success', 'Pizza opgeslagen.');

            return $this->redirectToRoute('app_pizza_index');
        }

        return $this->render('pizza/new.html.twig', [
            'form' => $form,
        ]);
    }
}
