<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

// Stuurt eigenaresse naar admin en medewerker naar staff na inloggen.
class LoginRedirectSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [LoginSuccessEvent::class => 'onLoginSuccess'];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        $roles = $user->getRoles();

        if (in_array('ROLE_OWNER', $roles, true)) {
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('kapsalon_admin_dashboard')));

            return;
        }

        if (in_array('ROLE_EMPLOYEE', $roles, true)) {
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('kapsalon_staff_dashboard')));
        }
    }
}
