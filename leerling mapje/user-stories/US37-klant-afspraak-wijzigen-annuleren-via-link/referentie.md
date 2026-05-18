# Code referentie — US37-klant-afspraak-wijzigen-annuleren-via-link

**Project:** `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde`

## Routes
- /afspraak/beheer/demo-token-...

## Bestanden
- `C:\Users\Gebruiker\Documents\GitHub\Symfony_einde\src/Controller/AppointmentController.php`

## Zo test je het
1. Open /afspraak/beheer/demo-token-1234567890abcdef

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
