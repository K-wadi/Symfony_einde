# Code referentie — US04-privacystatement

**Project:** `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde`

## Routes
- GET `/privacy` — `kapsalon_privacy`
- GET `/algemene-voorwaarden` — `kapsalon_terms`
- GET|POST `/cookie-instellingen` — `kapsalon_cookie_settings`

## Bestanden
- `src/Controller/PrivacyController.php`
- `src/Controller/TermsController.php`
- `templates/kapsalon/privacy/index.html.twig`
- `templates/kapsalon/privacy/cookies.html.twig`
- `templates/kapsalon/terms/index.html.twig`
- Checkbox `acceptTerms` op `AppointmentBookingType` en `CheckoutType`

## Zo test je het
1. Open Privacy en Algemene voorwaarden in nav/footer
2. Reserveren/checkout zonder AV-vinkje → validatiefout

## Demo accounts (fixtures)
- Eigenaresse: `eigenaresse@kapper.nl` / `test1234`
