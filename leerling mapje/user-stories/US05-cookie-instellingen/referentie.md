# Code referentie — US05-cookie-instellingen

**Project:** `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde`

## Routes
- GET|POST `/cookie-instellingen` — `kapsalon_cookie_settings`
- POST `/cookies` — `kapsalon_cookies_save` (banner)

## Bestanden
- `src/Controller/PrivacyController.php`
- `templates/kapsalon/privacy/cookies.html.twig`
- `templates/base.html.twig` — banner + GA alleen bij `cookie_consent == 'all'`
- `.env` — `GOOGLE_ANALYTICS_ID`

## Zo test je het
1. Eerste bezoek: cookiebanner → Instellingen of Alles accepteren
2. View source: GA-script alleen na “all”
