# Code referentie — US35-klant-bevestiging-sms-en-email

**Project:** `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde`

## Routes
- Reserveren (modal of `/afspraak/reserveren`)
- GET `/admin/sms-log` — demo SMS-overzicht

## Bestanden
- `src/Service/NotificationService.php` — mail + absolute beheer-URL
- `src/Service/SmsNotificationService.php` — DB + `var/log/sms.log`
- `src/Entity/SmsNotification.php`
- `src/Controller/Admin/SmsLogController.php`

## Zo test je het
1. Maak afspraak → flash “e-mail en SMS”
2. Admin → SMS-log: regel met telefoon en bericht
