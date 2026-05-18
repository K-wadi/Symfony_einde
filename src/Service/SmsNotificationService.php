<?php

namespace App\Service;

use App\Entity\SmsNotification;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

// Slaat SMS op in de database en schrijft naar het sms-log (geen echte provider).
class SmsNotificationService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface $smsLogger,
    ) {
    }

    public function send(string $phone, string $message): void
    {
        $record = new SmsNotification();
        $record->setPhone($phone);
        $record->setMessage($message);
        $this->em->persist($record);
        $this->em->flush();

        $this->smsLogger->info(sprintf('SMS naar %s: %s', $phone, $message));
    }
}
