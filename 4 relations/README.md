# Opdracht 4 – Relations (Many-to-Many + Custom Forms)

Los project: **Many-to-Many** (`Opdracht - Many-toMany.pdf`) + **Custom Forms** (`Opdracht - forms.pdf`).

## Installatie

```bash
cd "4 relations"
composer install
php bin/console doctrine:migrations:migrate
php bin/console app:seed-relations
symfony server:start
```

Database: **relations_oefen**

## Routes

### Many-to-Many

| Opdracht | URL |
|----------|-----|
| 1.2 + 1.3 | http://localhost:8000/show_information/books |
| 1.4 | http://localhost:8000/show_information/students |
| Overzicht | http://localhost:8000/show_information |

### Custom forms

| Opdracht | URL |
|----------|-----|
| 1.1 `form(form)` | `/pizza/new/simple` |
| 1.3 `form_rows` | `/pizza/new` |
| 1.5 + 2 handmatig | `/customer/new` |

## Vijf MtM-concepten

Zie `OPDRACHT_1.1_MTM_CONCEPTEN.md` – twee volledig gebouwd in code.

## Bronnen

- `Opdracht - Many-toMany.pdf`
- `Opdracht - forms.pdf`
- `symfony/P08W02L01 - Presentatie Symfony Many-to-Many.pptx`
- `symfony/P08W03L01 - Presentatie Symfony custom forms.pptx`
- `symfony/P05W07L01 - Presentatie Symfony Basic - relations.pptx`
- https://symfony.com/doc/6.4/forms.html
- https://getbootstrap.com/
