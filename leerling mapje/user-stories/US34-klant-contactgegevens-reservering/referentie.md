# Code referentie — US34-klant-contactgegevens-reservering

**Project:** `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde`

## Routes
- /afspraak/reserveren

## Bestanden
- `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde\src/Form/AppointmentBookingType.php`

## Zo test je het
1. Enter name email phone

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
