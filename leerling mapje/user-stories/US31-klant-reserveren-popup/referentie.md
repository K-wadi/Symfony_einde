# Code referentie — US31-klant-reserveren-popup

**Project:** `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde`

## Routes
- Modal op alle pagina's via `render(controller('App\\Controller\\AppointmentController::bookModal'))`
- GET `/afspraak/reserveren` — volledige pagina

## Bestanden
- `templates/base.html.twig` — `#reserveModal`
- `src/Controller/AppointmentController.php` — `bookModal()`
- `templates/kapsalon/appointment/_modal_form.html.twig`

## Zo test je het
1. Klik “Afspraak maken” (rechtsonder)
2. Vul formulier in pop-up in; na succes redirect naar home met flash
