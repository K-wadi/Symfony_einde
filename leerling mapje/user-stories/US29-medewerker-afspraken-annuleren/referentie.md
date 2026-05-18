# Code referentie — US29-medewerker-afspraken-annuleren

**Project:** `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde`

## Routes
- POST /staff/afspraken/{id}/annuleren

## Bestanden
- `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde\src/Controller/Staff/AppointmentController.php`

## Zo test je het
1. Cancel as staff

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
