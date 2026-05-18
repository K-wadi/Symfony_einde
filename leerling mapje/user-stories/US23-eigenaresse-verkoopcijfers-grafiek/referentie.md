# Code referentie — US23-eigenaresse-verkoopcijfers-grafiek

## Routes
- GET `/admin/statistieken` — filter `dateFrom`, `dateTo`

## Bestanden
- `src/Controller/Admin/StatsController.php`
- `src/Repository/ShopOrderRepository.php` — `getTotalsBetween()`
- `templates/kapsalon/admin/stats.html.twig` — Chart.js

## Zo test je het
1. Login eigenaresse → Statistieken → pas periode aan → grafiek wijzigt
