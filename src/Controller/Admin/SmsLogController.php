<?php

namespace App\Controller\Admin;

use App\Repository\SmsNotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/sms-log')]
#[IsGranted('ROLE_OWNER')]
// Admin: overzicht verzonden SMS-berichten.
class SmsLogController extends AbstractController
{
    #[Route('', name: 'kapsalon_admin_sms_log')]
    public function index(SmsNotificationRepository $repository): Response
    {
        return $this->render('kapsalon/admin/sms_log.html.twig', [
            'messages' => $repository->findLatest(50),
        ]);
    }
}
