# Opdracht 5 – Shopping Cart

Los project voor **Opdracht - shopping cart.pdf** + presentatie `shopping cart.pptx`.

## Installatie

```bash
cd "5 shopping cart"
composer install
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
symfony server:start
```

Database: **shopping_cart**

## Routes

| Stap | URL |
|------|-----|
| Producten (homepage) | http://localhost:8000/ |
| Buy-knop | `/product/add/{id}` |
| Winkelwagen | http://localhost:8000/cart/ |
| Legen | POST `/cart/clear` |
| +/- aantal | `/cart/increase/{id}`, `/cart/decrease/{id}` |
| Verwijderen | POST `/cart/remove/{id}` |

## Onderdelen

- `Product` entity + `ProductFixtures` (10 producten)
- `Order` + `OrderLine` (relatie `purchase` i.p.v. `order` in SQL)
- `src/Storage/CartSessionStorage.php` (ROC Mondriaan + stap 16 methodes)
- `CartExtension` – teller in navbar
- Checkout-formulier → opslaan in database

## Bronnen

- `Opdracht - shopping cart.pdf`
- `symfony/shopping cart.pptx`
- `symfony/P08W07L01 - Presentatie Symfony Session.pptx` (sessies)
- https://github.com/ROCMondriaanTIN/shopping-cart (CartSessionStorage)
- https://symfony.com/
- https://getbootstrap.com/
