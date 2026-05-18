# Code referentie — US36-klant-wijziging-bevestiging-sms-en-email

## Routes
- GET|POST `/afspraak/beheer/{token}`

## Bestanden
- `src/Controller/AppointmentController.php` — `manage()` + `NotificationService::sendAppointmentConfirmation(..., 'updated')`

## Zo test je het
1. Open demo-token URL, wijzig tijd → flash e-mail en SMS
