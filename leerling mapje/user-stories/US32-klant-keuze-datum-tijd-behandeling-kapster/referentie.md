# Code referentie — US32-klant-keuze-datum-tijd-behandeling-kapster

**Project:** `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde`

## Routes
- /afspraak/reserveren

## Bestanden
- `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde\src/Form/AppointmentBookingType.php`

## Zo test je het
1. Fill booking form

## Demo accounts (fixtures)
- Eigenaresse: `eigenaresse@kapper.nl` / `test1234`
- Medewerker: `anita@kapper.nl` / `test1234`

## Opstarten
```bash
cd C:\Users\Gebruiker\Documents\GitHub\Symfony_einde
php bin/console cache:clear
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load -n
symfony server:start
```
