-- Database De snelle ingreep (opdracht roles)
CREATE DATABASE IF NOT EXISTS `snelle_ingreep` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `snelle_ingreep`;

-- Tabellen worden normaal via Doctrine migration aangemaakt.
-- Na migrate: php bin/console app:seed-specialists
-- Optioneel admin handmatig in phpMyAdmin met roles ["ROLE_ADMIN"] en user_type admin
