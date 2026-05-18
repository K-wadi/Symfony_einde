# Code referentie — US02-homepage-specialisatie

**Project:** `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde`

## Routes
- GET /

## Bestanden
- `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde\templates/kapsalon/home/index.html.twig`

## Zo test je het
1. See specialisatie section on home

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
