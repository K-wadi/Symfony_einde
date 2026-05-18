<?php

namespace App\Service;

use App\Entity\Appointment;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

// Stuurt bevestiging per e-mail en SMS na reserveren of wijzigen van een afspraak.
class NotificationService
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly SmsNotificationService $sms,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly string $salonEmail = 'jehaarzitgoed@kapper.nl',
    ) {
    }

    public function sendAppointmentConfirmation(Appointment $appointment, string $context = 'created'): void
    {
        $subject = $context === 'updated'
            ? 'Uw afspraak is gewijzigd'
            : 'Bevestiging van uw afspraak';

        $manageUrl = $this->urlGenerator->generate(
            'kapsalon_appointment_manage',
            ['token' => $appointment->getManageToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $body = sprintf(
            "Beste %s,\n\nUw afspraak bij Je haar zit goed:\n- Behandeling: %s\n- Kapster: %s\n- Datum/tijd: %s\n\nBeheer uw afspraak: %s\n",
            $appointment->getCustomerName(),
            $appointment->getTreatment()?->getName(),
            $appointment->getEmployee()?->getName(),
            $appointment->getStartsAt()?->format('d-m-Y H:i'),
            $manageUrl
        );

        $email = (new Email())
            ->from($this->salonEmail)
            ->to($appointment->getCustomerEmail())
            ->subject($subject)
            ->text($body);

        try {
            $this->mailer->send($email);
        } catch (\Throwable) {
            // Geen echte mail als MAILER_DSN=null (lokaal testen).
        }

        if ($appointment->getCustomerPhone()) {
            $this->sms->send($appointment->getCustomerPhone(), $body);
        }
    }
}
