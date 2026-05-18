<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Privacytekst en cookievoorkeuren (essential of alles incl. analytics).
class PrivacyController extends AbstractController
{
    #[Route('/privacy', name: 'kapsalon_privacy')]
    public function index(): Response
    {
        return $this->render('kapsalon/privacy/index.html.twig');
    }

    #[Route('/cookie-instellingen', name: 'kapsalon_cookie_settings')]
    public function cookieSettings(Request $request): Response
    {
        $current = $request->getSession()->get('cookie_consent');

        if ($request->isMethod('POST')) {
            $choice = $request->request->get('cookie_choice', 'essential');
            $request->getSession()->set('cookie_consent', $choice);
            $this->addFlash('success', 'Cookievoorkeuren opgeslagen.');

            return $this->redirectToRoute('kapsalon_cookie_settings');
        }

        return $this->render('kapsalon/privacy/cookies.html.twig', [
            'current' => $current,
        ]);
    }

    #[Route('/cookies', name: 'kapsalon_cookies_save', methods: ['POST'])]
    public function saveCookies(Request $request): Response
    {
        $choice = $request->request->get('cookie_choice', 'essential');
        $request->getSession()->set('cookie_consent', $choice);
        $this->addFlash('success', 'Cookievoorkeuren opgeslagen.');

        return $this->redirect($request->headers->get('referer', '/'));
    }
}
