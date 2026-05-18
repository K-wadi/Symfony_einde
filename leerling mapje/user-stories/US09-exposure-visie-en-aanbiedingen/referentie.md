# Code referentie — US09-exposure-visie-en-aanbiedingen

## Routes
- GET `/` — `app_home`

## Bestanden
- `templates/kapsalon/home/index.html.twig` — visie, specialisatie, fotogalerie, aanbiedingen, “Voor jou”
- `src/Controller/HomeController.php` — `OfferRecommendationService`
- `public/images/salon/*.svg`

## Zo test je het
1. Home toont visie, foto's, aanbiedingen en (na bestelling) blok “Voor jou”
