<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Als gebruiker al ingelogd is, redirect naar home
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // Haal login error op als die er is
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // Laatste gebruikersnaam die werd ingevoerd
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
        
        // TODO: Twig template aanmaken voor login form
        // - Email/password velden
        // - CSRF protection
        // - Error messages weergeven
        // - Link naar registratie pagina
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Deze methode kan leeg blijven - Symfony security handelt logout af
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $plainPassword = $form->get('plainPassword')->getData();
                    
                    // Debug informatie
                    error_log("Registration attempt for: " . $user->getEmail());
                    error_log("Plain password length: " . strlen($plainPassword));
                    
                    // Encode het wachtwoord
                    $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
                    $user->setPassword($hashedPassword);
                    
                    error_log("Generated hash: " . substr($hashedPassword, 0, 30) . "...");

                    // Standaard rol toekennen
                    $user->setRoles(['ROLE_USER']);

                    $entityManager->persist($user);
                    $entityManager->flush();

                    $this->addFlash('success', 'Registratie succesvol! Je kunt nu inloggen met ' . $user->getEmail());

                    return $this->redirectToRoute('app_login');
                } catch (\Exception $e) {
                    error_log("Registration error: " . $e->getMessage());
                    $this->addFlash('error', 'Er is een fout opgetreden bij het registreren: ' . $e->getMessage());
                }
            } else {
                // Debug formulier fouten
                $errors = [];
                foreach ($form->getErrors(true) as $error) {
                    $errors[] = $error->getMessage();
                }
                error_log("Form validation errors: " . implode(", ", $errors));
                $this->addFlash('error', 'Er zijn fouten in het formulier: ' . implode(", ", $errors));
            }
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
        
        // TODO: Twig template aanmaken voor registratie form
        // - Email, password, password confirmation velden
        // - Validatie errors weergeven
        // - CSRF protection
        // - Link naar login pagina
    }
} 