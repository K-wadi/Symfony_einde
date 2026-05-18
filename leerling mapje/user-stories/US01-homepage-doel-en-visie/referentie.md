# Code referentie — US01-homepage-doel-en-visie

**Project:** `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde`

## Routes
- GET / (app_home)

## Bestanden
- `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde\src/Controller/HomeController.php`
- `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde\templates/kapsalon/home/index.html.twig`

## Zo test je het
1. Open homepage
1. See visie/doel in hero section

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
