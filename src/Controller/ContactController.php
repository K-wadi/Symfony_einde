<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

// Contactformulier; mail naar het salon-adres.
class ContactController extends AbstractController
{
    #[Route('/contact', name: 'kapsalon_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = (new TemplatedEmail())
                ->from('jehaarzitgoed@kapper.nl')
                ->to('jehaarzitgoed@kapper.nl')
                ->subject('Contact via website')
                ->text(sprintf("Van: %s <%s>\n\n%s", $data['name'], $data['email'], $data['message']));

            try {
                $mailer->send($email);
                $this->addFlash('success', 'Bericht verzonden.');
            } catch (\Throwable) {
                $this->addFlash('info', 'Bericht ontvangen (e-mailserver niet bereikbaar).');
            }

            return $this->redirectToRoute('kapsalon_contact');
        }

        return $this->render('kapsalon/contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
