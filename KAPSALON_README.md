# Kapsalon — Je haar zit goed (Symfony MVP)

Alle 43 user stories zijn geïmplementeerd in dit project.

## Starten

```bash
composer install
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load -n
php bin/console cache:clear
symfony server:start
# of: php -S localhost:8000 -t public
```

Database: SQLite `var/data_kapsalon.db` (zie `.env` / `.env.local`). Voor MySQL: pas `DATABASE_URL` aan.

## Demo login

| Rol | E-mail | Wachtwoord |
|-----|--------|------------|
| Eigenaresse | eigenaresse@kapper.nl | test1234 |
| Medewerker | anita@kapper.nl | test1234 |

Demo afspraak beheren: `/afspraak/beheer/demo-token-1234567890abcdef`

## Demo vs productie

| Functie | Demo | Productie |
|---------|------|-----------|
| E-mail | `MAILER_DSN=null://null` (geen verzending) | Gmail: `gmail+smtp://...` |
| SMS | `SmsNotificationService` → DB + `var/log/sms.log` | Koppel echte SMS-API |
| Google Analytics | `GOOGLE_ANALYTICS_ID` in `.env`; script alleen na cookie “Alles accepteren” | Echt GA4-meet-ID |
| Bezoekers | `PageVisit`-tabel + optioneel GA | GA-dashboard |

## Belangrijke routes

- `/` — Home (visie, foto's, contact, aanbiedingen)
- `/contact` — Contactformulier
- `/privacy` — Privacystatement
- `/algemene-voorwaarden` — AV
- `/cookie-instellingen` — Cookievoorkeuren
- `/afspraak/reserveren` — Reserveren (+ pop-up op elke pagina)
- `/admin` — Dashboard eigenaresse
- `/admin/statistieken` — Verkoop periode, gedrag, bezoekfrequentie
- `/admin/sms-log` — Verzonden SMS (demo)

## User story referenties

Zie per story: `D:\resources\leerling mapje\user-stories\USxx-...\referentie.md`
