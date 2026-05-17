-- Database smartphone4u (presentatie form slide 7)
-- Importeer in phpMyAdmin of: mysql -u root < smartphone4u.sql

CREATE DATABASE IF NOT EXISTS `smartphone4u` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `smartphone4u`;

CREATE TABLE IF NOT EXISTS `vendor` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `smartphone` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `type` VARCHAR(255) NOT NULL,
    `memory` INT NOT NULL,
    `color` VARCHAR(255) NOT NULL,
    `price` NUMERIC(7, 2) NOT NULL,
    `description` LONGTEXT DEFAULT NULL,
    `picture` VARCHAR(255) DEFAULT NULL,
    `vendor_id` INT NOT NULL,
    INDEX `IDX_SMARTPHONE_VENDOR` (`vendor_id`),
    PRIMARY KEY (`id`),
    CONSTRAINT `FK_SMARTPHONE_VENDOR` FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `vendor` (`id`, `name`) VALUES
(1, 'Apple'),
(2, 'Samsung'),
(3, 'Google');

INSERT INTO `smartphone` (`id`, `type`, `memory`, `color`, `price`, `description`, `picture`, `vendor_id`) VALUES
(1, 'iPhone 15', 128, 'Zwart', 899.00, 'Nieuwste iPhone', NULL, 1),
(2, 'Galaxy S24', 256, 'Blauw', 799.99, 'Android flagship', NULL, 2),
(3, 'Pixel 8', 128, 'Wit', 699.00, 'Google smartphone', NULL, 3);
