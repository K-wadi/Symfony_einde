-- =============================================================================
-- Kapsalon Je haar zit goed — volledig MySQL-schema
-- Database: kapsalon
-- Komt overeen met Symfony-entities in src/Entity/
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP DATABASE IF EXISTS kapsalon;
CREATE DATABASE kapsalon
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE kapsalon;

-- -----------------------------------------------------------------------------
-- Gebruikers en medewerkers
-- -----------------------------------------------------------------------------

CREATE TABLE `user` (
    id INT AUTO_INCREMENT NOT NULL,
    email VARCHAR(180) NOT NULL,
    roles JSON NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) DEFAULT NULL,
    last_name VARCHAR(100) DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE employee (
    id INT AUTO_INCREMENT NOT NULL,
    user_id INT DEFAULT NULL,
    name VARCHAR(120) NOT NULL,
    specialization VARCHAR(120) DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE INDEX UNIQ_5D9F75A1A76ED395 (user_id),
    CONSTRAINT FK_5D9F75A1A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Behandelingen, producten, aanbiedingen
-- -----------------------------------------------------------------------------

CREATE TABLE treatment (
    id INT AUTO_INCREMENT NOT NULL,
    name VARCHAR(150) NOT NULL,
    description LONGTEXT DEFAULT NULL,
    duration_minutes INT NOT NULL,
    price_cents INT NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE salon_product (
    id INT AUTO_INCREMENT NOT NULL,
    name VARCHAR(150) NOT NULL,
    description LONGTEXT DEFAULT NULL,
    category VARCHAR(80) DEFAULT NULL,
    price_cents INT NOT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE offer (
    id INT AUTO_INCREMENT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description LONGTEXT DEFAULT NULL,
    active TINYINT(1) NOT NULL DEFAULT 1,
    valid_until DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Afspraken en rooster
-- -----------------------------------------------------------------------------

CREATE TABLE appointment (
    id INT AUTO_INCREMENT NOT NULL,
    employee_id INT NOT NULL,
    treatment_id INT NOT NULL,
    customer_name VARCHAR(120) NOT NULL,
    customer_email VARCHAR(180) NOT NULL,
    customer_phone VARCHAR(30) DEFAULT NULL,
    starts_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    status VARCHAR(20) NOT NULL DEFAULT 'planned',
    manage_token VARCHAR(64) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE INDEX UNIQ_FE38F844BF2965B1 (manage_token),
    INDEX IDX_FE38F8448C03F15C (employee_id),
    INDEX IDX_FE38F844471C0366 (treatment_id),
    CONSTRAINT FK_FE38F8448C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id),
    CONSTRAINT FK_FE38F844471C0366 FOREIGN KEY (treatment_id) REFERENCES treatment (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE time_block (
    id INT AUTO_INCREMENT NOT NULL,
    employee_id INT NOT NULL,
    start_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    end_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    PRIMARY KEY (id),
    INDEX IDX_C669765E8C03F15C (employee_id),
    CONSTRAINT FK_C669765E8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Webwinkel
-- -----------------------------------------------------------------------------

CREATE TABLE shop_order (
    id INT AUTO_INCREMENT NOT NULL,
    customer_name VARCHAR(120) NOT NULL,
    customer_email VARCHAR(180) NOT NULL,
    delivery_method VARCHAR(20) NOT NULL DEFAULT 'pickup',
    created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    PRIMARY KEY (id),
    INDEX IDX_shop_order_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE shop_order_line (
    id INT AUTO_INCREMENT NOT NULL,
    shop_order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    INDEX IDX_E69663C7562797AE (shop_order_id),
    INDEX IDX_E69663C74584665A (product_id),
    CONSTRAINT FK_E69663C7562797AE FOREIGN KEY (shop_order_id) REFERENCES shop_order (id) ON DELETE CASCADE,
    CONSTRAINT FK_E69663C74584665A FOREIGN KEY (product_id) REFERENCES salon_product (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Abonnement, bezoekers, SMS
-- -----------------------------------------------------------------------------

CREATE TABLE subscription (
    id INT AUTO_INCREMENT NOT NULL,
    customer_name VARCHAR(120) NOT NULL,
    customer_email VARCHAR(180) NOT NULL,
    plan_name VARCHAR(80) NOT NULL,
    price_cents INT NOT NULL,
    visits_included INT NOT NULL DEFAULT 4,
    created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE page_visit (
    id INT AUTO_INCREMENT NOT NULL,
    path VARCHAR(255) NOT NULL,
    visited_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    visitor_key VARCHAR(64) DEFAULT NULL,
    PRIMARY KEY (id),
    INDEX IDX_page_visit_path (path)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sms_notification (
    id INT AUTO_INCREMENT NOT NULL,
    phone VARCHAR(30) NOT NULL,
    message LONGTEXT NOT NULL,
    sent_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    PRIMARY KEY (id),
    INDEX IDX_sms_notification_sent_at (sent_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Symfony Messenger (optioneel, gebruikt door MESSENGER_TRANSPORT_DSN)
-- -----------------------------------------------------------------------------

CREATE TABLE messenger_messages (
    id BIGINT AUTO_INCREMENT NOT NULL,
    body LONGTEXT NOT NULL,
    headers LONGTEXT NOT NULL,
    queue_name VARCHAR(190) NOT NULL,
    created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
    PRIMARY KEY (id),
    INDEX IDX_75EA56E0FB7336F0 (queue_name),
    INDEX IDX_75EA56E0E3BD61CE (available_at),
    INDEX IDX_75EA56E016BA31DB (delivered_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
-- Optionele testdata (zelfde als KapsalonFixtures)
-- Wachtwoord voor alle accounts: test1234
-- =============================================================================

-- bcrypt hash van 'test1234'
SET @pwd = '$2y$10$dCr4fdSh66gF8Im8n/78res.klAoNUML4koyOYheDoFwTmuCc26fi';

INSERT INTO `user` (id, email, roles, password, first_name, last_name) VALUES
(1, 'eigenaresse@kapper.nl', '["ROLE_OWNER"]', @pwd, 'Karin', 'de Vries'),
(2, 'anita@kapper.nl', '["ROLE_EMPLOYEE"]', @pwd, 'Anita', NULL),
(3, 'manon@kapper.nl', '["ROLE_EMPLOYEE"]', @pwd, 'Manon', NULL),
(4, 'freek@kapper.nl', '["ROLE_EMPLOYEE"]', @pwd, 'Freek', NULL);

INSERT INTO employee (id, user_id, name, specialization) VALUES
(1, 2, 'Anita', 'Kleuren'),
(2, 3, 'Manon', 'Knippen'),
(3, 4, 'Freek', 'Heren');

INSERT INTO treatment (id, name, description, duration_minutes, price_cents) VALUES
(1, 'Knippen dames', 'Professionele behandeling met Kérastase producten.', 45, 3500),
(2, 'Kleuren', 'Professionele behandeling met Kérastase producten.', 90, 7500),
(3, 'Highlights', 'Professionele behandeling met Kérastase producten.', 120, 9500),
(4, 'Wassen & föhnen', 'Professionele behandeling met Kérastase producten.', 30, 2500);

INSERT INTO salon_product (id, name, description, category, price_cents, image_url) VALUES
(1, 'Product 1', 'Haarverzorging product 1', 'Shampoo', 1700, 'https://placehold.co/400x300/6b8e23/fff?text=Product+1'),
(2, 'Product 2', 'Haarverzorging product 2', 'Styling', 1900, 'https://placehold.co/400x300/6b8e23/fff?text=Product+2'),
(3, 'Product 3', 'Haarverzorging product 3', 'Shampoo', 2100, 'https://placehold.co/400x300/6b8e23/fff?text=Product+3'),
(4, 'Product 4', 'Haarverzorging product 4', 'Styling', 2300, 'https://placehold.co/400x300/6b8e23/fff?text=Product+4'),
(5, 'Product 5', 'Haarverzorging product 5', 'Shampoo', 2500, 'https://placehold.co/400x300/6b8e23/fff?text=Product+5'),
(6, 'Product 6', 'Haarverzorging product 6', 'Styling', 2700, 'https://placehold.co/400x300/6b8e23/fff?text=Product+6'),
(7, 'Product 7', 'Haarverzorging product 7', 'Shampoo', 2900, 'https://placehold.co/400x300/6b8e23/fff?text=Product+7'),
(8, 'Product 8', 'Haarverzorging product 8', 'Styling', 3100, 'https://placehold.co/400x300/6b8e23/fff?text=Product+8'),
(9, 'Product 9', 'Haarverzorging product 9', 'Shampoo', 3300, 'https://placehold.co/400x300/6b8e23/fff?text=Product+9'),
(10, 'Product 10', 'Haarverzorging product 10', 'Styling', 3500, 'https://placehold.co/400x300/6b8e23/fff?text=Product+10');

INSERT INTO offer (id, title, description, active, valid_until) VALUES
(1, 'Voorjaarsactie', '20% korting op kleurbehandelingen deze maand.', 1, NULL);

INSERT INTO time_block (id, employee_id, start_at, end_at) VALUES
(1, 1, '2026-05-25 09:00:00', '2026-05-25 17:00:00'),
(2, 2, '2026-05-25 09:00:00', '2026-05-25 17:00:00'),
(3, 3, '2026-05-25 09:00:00', '2026-05-25 17:00:00');

INSERT INTO appointment (id, employee_id, treatment_id, customer_name, customer_email, customer_phone, starts_at, status, manage_token) VALUES
(1, 1, 1, 'Demo Klant', 'klant@example.nl', '0612345678', '2026-05-26 10:00:00', 'planned', 'demo-token-1234567890abcdef');

-- Voorbeeld bestelling (voor statistieken / gedrag)
INSERT INTO shop_order (id, customer_name, customer_email, delivery_method, created_at) VALUES
(1, 'Jan Jansen', 'jan@example.nl', 'pickup', '2026-04-01 14:30:00'),
(2, 'Jan Jansen', 'jan@example.nl', 'pickup', '2026-04-15 11:00:00');

INSERT INTO shop_order_line (id, shop_order_id, product_id, quantity) VALUES
(1, 1, 1, 2),
(2, 1, 3, 1),
(3, 2, 2, 1);

INSERT INTO subscription (id, customer_name, customer_email, plan_name, price_cents, visits_included, created_at) VALUES
(1, 'Lisa de Boer', 'lisa@example.nl', 'Knipabonnement 4x', 12000, 4, '2026-03-10 09:00:00');

INSERT INTO page_visit (id, path, visited_at, visitor_key) VALUES
(1, '/', '2026-05-18 08:00:00', 'sess-demo-1'),
(2, '/behandelingen', '2026-05-18 08:05:00', 'sess-demo-1'),
(3, '/producten', '2026-05-18 08:10:00', 'sess-demo-2');

INSERT INTO sms_notification (id, phone, message, sent_at) VALUES
(1, '0612345678', 'Beste Demo Klant, uw afspraak bij Je haar zit goed is bevestigd.', '2026-05-17 16:00:00');
