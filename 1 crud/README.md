# Opdracht 1 – CRUD Smartphones

Los Symfony-project voor de opdracht **Opdracht - crud.pdf**.  
Staat los van de hoofdcodebase in de repo-root.

## Vereisten

- PHP 8.1+
- Composer
- MySQL / MariaDB (XAMPP)
- Symfony CLI (optioneel, voor `symfony server:start`)

## Installatie

```bash
cd "1 crud"
composer install
```

1. Importeer `smartphone4u.sql` in phpMyAdmin (database **smartphone4u**).
2. Pas `.env` aan als je andere DB-gebruikers/wachtwoord hebt.
3. Of voer migraties uit (als tabellen nog leeg zijn):

```bash
php bin/console doctrine:migrations:migrate
```

4. Start de server:

```bash
symfony server:start
# of: php -S localhost:8000 -t public
```

5. Open: http://localhost:8000/smartphone/

## Wat zit erin (opdracht PDF)

| Onderdeel | Route / bestand |
|-----------|-----------------|
| **Read** – overzicht met kolom `nr` (loop.index) | `/smartphone/` |
| **Create** – formulier + validatie + flash | `/smartphone/new` |
| **Update** – bewerken + flash | `/smartphone/{id}/edit` |
| **Delete** – eerst details, dan verwijderen + flash | `/smartphone/{id}/delete` |
| **Details** | `/smartphone/{id}` |

## ERD (tabellen)

- **vendor**: id, name  
- **smartphone**: id, type, memory, color, price, description, picture, vendor_id  

Velden volgens presentatie `P05W04L02 - Presentatie Symfony Basic - form.pptx` (slide 7).

## Bronnen gebruikt

- `Opdracht - crud.pdf`
- `symfony/P05W03L02 - Presentatie Symfony Basic - crud.pptx`
- `symfony/P05W04L02 - Presentatie Symfony Basic - create.pptx`
- `symfony/P05W04L02 - Presentatie Symfony Basic - form.pptx`
- `symfony/P05W07L01 - Presentatie Symfony Basic - update.pptx`
- `symfony/P05W07L02 - Presentatie Symfony Basic - delete.pptx`
- `symfony/P05W07L01 - Presentatie Symfony Basic - relations.pptx`
- https://symfony.com/doc/current/controller/upload_file.html
- https://getbootstrap.com/ (Bootstrap 5 + Icons via CDN)
