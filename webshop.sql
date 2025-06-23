-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2025 at 01:59 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webshop`
--
CREATE DATABASE IF NOT EXISTS `webshop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `webshop`;

-- --------------------------------------------------------

--
-- Table structure for table `bestelling`
--

CREATE TABLE `bestelling` (
  `id` int(11) NOT NULL,
  `klantnaam` varchar(255) NOT NULL,
  `datum` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bestelling`
--

INSERT INTO `bestelling` (`id`, `klantnaam`, `datum`) VALUES
(1, 'Test Klant', '2025-06-23 01:37:39'),
(2, 'Khaled', '2025-06-23 01:47:09');

-- --------------------------------------------------------

--
-- Table structure for table `bestelling_product`
--

CREATE TABLE `bestelling_product` (
  `bestelling_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bestelling_product`
--

INSERT INTO `bestelling_product` (`bestelling_id`, `product_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 14);

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250622233234', '2025-06-23 01:33:13', 140);

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `prijs` decimal(10,2) NOT NULL,
  `beschrijving` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `naam`, `prijs`, `beschrijving`) VALUES
(1, 'Laptop Dell XPS 13', 999.99, 'Krachtige ultrabook voor professionals'),
(2, 'iPhone 15', 899.00, 'Nieuwste Apple smartphone'),
(3, 'Samsung Galaxy S24', 799.99, 'Android flagship telefoon'),
(4, 'iPad Air', 649.00, 'Veelzijdige tablet voor werk en ontspanning'),
(5, 'MacBook Air M2', 1199.00, 'Apple laptop met M2 chip'),
(6, 'Sony WH-1000XM5', 399.99, 'Noise-cancelling koptelefoon'),
(7, 'Laptop Dell XPS 13', 999.99, 'Krachtige ultrabook met Intel i7 processor'),
(8, 'iPhone 15', 899.00, 'Nieuwste iPhone met A17 Pro chip'),
(9, 'Samsung Galaxy S24', 799.99, 'Android smartphone met AI functies'),
(10, 'MacBook Air M3', 1299.00, 'Apple laptop met M3 chip'),
(11, 'iPad Pro', 699.99, 'Professionele tablet voor creatieven'),
(12, 'AirPods Pro', 249.99, 'Draadloze oordopjes met noise cancelling'),
(13, 'Laptop Dell XPS 13', 999.99, 'Krachtige ultrabook met Intel i7 processor'),
(14, 'iPhone 15', 899.00, 'Nieuwste iPhone met A17 Pro chip'),
(15, 'Samsung Galaxy S24', 799.99, 'Android smartphone met AI functies'),
(16, 'MacBook Air M3', 1299.00, 'Apple laptop met M3 chip'),
(17, 'iPad Pro', 699.99, 'Professionele tablet voor creatieven'),
(18, 'AirPods Pro', 249.99, 'Draadloze oordopjes met noise cancelling');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES
(1, 'admin@webshop.nl', '[\"ROLE_ADMIN\"]', '$2y$13$O611wsfDu6UNgiRPMunBtOsnkJx4SoCqCvrSCEhlRoGADGIXUw1ma'),
(2, 'user@webshop.nl', '[\"ROLE_USER\"]', '$2y$13$O611wsfDu6UNgiRPMunBtOsnkJx4SoCqCvrSCEhlRoGADGIXUw1ma'),
(7, 'test@example.com', '[\"ROLE_USER\"]', '$2y$10$X/qUvYv5Lj1VZ.r3muQsz.E0Ya2u1xgyUjYXljuwUDx2NanlJA8Ii'),
(8, 'newuser@test.com', '[\"ROLE_USER\"]', '$2y$13$h7l3kpBe98BWzvJ/v8k26.voJpiJ/Zx/HBsTuqvtkW6nQJuX4bSCO');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bestelling`
--
ALTER TABLE `bestelling`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bestelling_product`
--
ALTER TABLE `bestelling_product`
  ADD PRIMARY KEY (`bestelling_id`,`product_id`),
  ADD KEY `IDX_D890D6A44584665A` (`product_id`),
  ADD KEY `IDX_D73B86FAA2E63037` (`bestelling_id`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bestelling`
--
ALTER TABLE `bestelling`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bestelling_product`
--
ALTER TABLE `bestelling_product`
  ADD CONSTRAINT `FK_D73B86FA4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_D73B86FAA2E63037` FOREIGN KEY (`bestelling_id`) REFERENCES `bestelling` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_D890D6A44584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_D890D6A4A4C98E8` FOREIGN KEY (`bestelling_id`) REFERENCES `bestelling` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
