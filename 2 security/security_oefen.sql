-- Database voor opdracht Security (periode 8 week 1)
CREATE DATABASE IF NOT EXISTS `security_oefen` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `security_oefen`;

CREATE TABLE IF NOT EXISTS `user` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `email` VARCHAR(180) NOT NULL,
    `roles` JSON NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `first_name` VARCHAR(255) NOT NULL,
    `last_name` VARCHAR(255) NOT NULL,
    `date_of_birth` DATE NOT NULL,
    UNIQUE INDEX UNIQ_8D93D649E7927C74 (`email`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `order` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `ordered_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `author` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `book` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `author_id` INT NOT NULL,
    INDEX IDX_CBE5A331F675F31B (`author_id`),
    PRIMARY KEY (`id`),
    CONSTRAINT FK_CBE5A331F675F31B FOREIGN KEY (`author_id`) REFERENCES `author` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Opdracht 4: auteurs en boeken
INSERT INTO `author` (`id`, `name`) VALUES
(1, 'J.R.R. Tolkien'),
(2, 'J.K. Rowling');

INSERT INTO `book` (`id`, `title`, `author_id`) VALUES
(1, 'Harry Potter and the Philosopher''s Stone', 2),
(2, 'Harry Potter and the Chamber of Secrets', 2),
(3, 'Harry Potter and the Prisoner of Azkaban', 2),
(4, 'Lord of the Rings Fellowship of the Ring', 1),
(5, 'Lord of the Rings the Two Towers', 1);

-- Demo bestellingen (opdracht 3.3)
INSERT INTO `order` (`id`, `description`, `ordered_at`) VALUES
(1, 'Bestelling #1001 - Smartphone hoesje', '2025-05-10 14:30:00'),
(2, 'Bestelling #1002 - Oplaadkabel', '2025-05-12 09:15:00');

-- Gebruikers: registreer via /register, zet daarna roles in phpMyAdmin:
-- ["ROLE_ADMIN"] voor admin
-- [] of ["ROLE_USER"] voor gewone users
