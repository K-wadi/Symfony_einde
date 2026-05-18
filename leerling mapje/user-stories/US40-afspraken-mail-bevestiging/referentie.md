# Code referentie — US40-afspraken-mail-bevestiging

**Project:** `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde`

## Routes
- booking flow

## Bestanden
- `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde\src/Service/NotificationService.php`

## Zo test je het
1. Same as US35 email part

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
