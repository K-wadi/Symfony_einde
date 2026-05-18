# Code referentie — US27-medewerker-inloggen-dashboard

**Project:** `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde`

## Routes
- GET /staff

## Bestanden
- `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde\src/Controller/Staff/DashboardController.php`

## Zo test je het
1. Login anita@kapper.nl / test1234

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
