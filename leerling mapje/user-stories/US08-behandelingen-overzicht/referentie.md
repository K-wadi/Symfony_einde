# Code referentie — US08-behandelingen-overzicht

**Project:** `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde`

## Routes
- GET /behandelingen

## Bestanden
- `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde\src/Controller/TreatmentController.php`

## Zo test je het
1. Open behandelingen page

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
