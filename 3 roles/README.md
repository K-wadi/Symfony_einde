# Opdracht 3 – Roles (Specialisten afspraken)

Ziekenhuis-app **De snelle ingreep** – los project in `3 roles/`.

## Installatie

```bash
cd "3 roles"
composer install
php bin/console doctrine:migrations:migrate
php bin/console app:seed-specialists
php bin/console app:seed-admin
symfony server:start
```

Database: **snelle_ingreep** (zie `.env`).

## Testaccounts

| Rol | E-mail | Wachtwoord |
|-----|--------|------------|
| Specialist 1 | anna.smit@snelle-ingreep.nl | specialist123 |
| Specialist 2 | peter.jansen@snelle-ingreep.nl | specialist123 |
| Patiënt | zelf registreren via `/register` | zelf gekozen |
| Admin | admin@snelle-ingreep.nl | admin123 |

## Opdracht ↔ route

| Stap | Wat | Route |
|------|-----|-------|
| 3 | Homepage bezoeker | `/guest` |
| 4 | Registratie patiënt | `/register` |
| 5 | Login + patiënt home | `/login`, `/patient/` |
| 6 | Specialist home + afspraken | `/specialist/` |
| 7 | Afspraken CRUD | `/specialist/appointments/...` |
| 8 | Bericht patiënt bij wijziging | meldingen op `/patient/` |
| Admin | Nieuws + gebruikers | `/admin/...` |

## Techniek (PDF)

- **Single Table Inheritance:** `User` → `Patient`, `Specialist`, `AdminUser` (kolom `user_type`)
- **Appointment:** relaties `patient` + `specialist`; op `User`: `patientAppointments` en `specialistAppointments`
- Alle velden in één `user`-tabel (presentatie roles 2)

## Bronnen

- `Opdracht - roles.pdf`
- `symfony/P07W01L02 - Presentatie Symfony Security - roles.pptx`
- `symfony/P08W01L02 - Presentatie Symfony Security - roles 2.pptx`
- `symfony/P07W01L01 - Presentatie Symfony Security - registratie form.pptx`
- https://symfony.com/ (Security, Doctrine inheritance)
- https://getbootstrap.com/
