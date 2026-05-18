# Code referentie — US13-klant-producten-ophalen-winkel

**Project:** `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde`

## Routes
- POST /winkelwagen/afrekenen

## Bestanden
- `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde\src/Form/CheckoutType.php`
- `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde\src/Entity/ShopOrder.php`

## Zo test je het
1. Choose ophalen in winkel at checkout

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
