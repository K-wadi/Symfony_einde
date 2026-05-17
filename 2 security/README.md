# Opdracht 2 – Security

Los Symfony-project voor **Opdracht - security.pdf** (periode 8, week 1).  
Staat los van `1 crud/` en de root-codebase.

## Installatie

```bash
cd "2 security"
composer install
```

1. Importeer `security_oefen.sql` in phpMyAdmin **of** run `php bin/console doctrine:migrations:migrate`
2. Start server: `symfony server:start` of `php -S localhost:8000 -t public`

## Routes om te testen

| Opdracht | URL | Wat test je? |
|----------|-----|----------------|
| 1 | `/register` | Registreren + user in database |
| 1 | `/login` | Inloggen |
| 2.1 | `/register` | Velden voornaam, achternaam, geboortedatum (NL labels) |
| 2.2 | navbar | Naam ingelogde gebruiker |
| 2.3 | `/users/`, `/users/add` | Gebruikers CRD + flash |
| 3.1 | `/` (home) | Rollen-loop na inloggen |
| 3.2 | navbar | Inloggen **of** uitloggen |
| 3.3 | menu + `/orders` | Bestellingen alleen ADMIN |
| 4 | `/books` (USER) | Boeken + auteur |
| 4 | `/admin/books`, `/admin/authors` | ADMIN wissen |

## Rollen instellen (opdracht 3.1 / 4)

1. Registreer 3 users via http://localhost:8000/register  
2. Open phpMyAdmin → tabel `user` → kolom `roles`  
3. Zet bij één user: `["ROLE_ADMIN"]`  
4. Laat bij de andere users `[]` staan (Symfony voegt automatisch `ROLE_USER` toe)

## Bronnen

- `Opdracht - security.pdf`
- `symfony/P07W01L01 - Presentatie Symfony Security - registratie form.pptx`
- `symfony/P07W01L02 - Presentatie Symfony Security - roles.pptx`
- `symfony/P08W01L02 - Presentatie Symfony Security - roles 2.pptx`
- https://symfony.com/ (Security, Form Login)
- https://getbootstrap.com/
