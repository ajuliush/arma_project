-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table mellodian.events
CREATE TABLE IF NOT EXISTS `events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `price_with_table` decimal(10,2) NOT NULL,
  `price_without_table` decimal(10,2) NOT NULL,
  `requires_adult` tinyint(1) DEFAULT '0',
  `seat_limit` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table mellodian.events: ~7 rows (approximately)
INSERT INTO `events` (`id`, `name`, `description`, `date`, `time`, `price_with_table`, `price_without_table`, `requires_adult`, `seat_limit`, `created_at`, `updated_at`) VALUES
	(5, 'Quamar Campbell', 'Minus dolore ab ipsa', '1970-01-11', '07:49:00', 533.00, 9.00, 1, 96, '2024-11-24 03:50:54', '2024-11-24 04:20:44'),
	(6, 'ksjfskjh', 'jkhdsfkjh', '2022-02-22', '12:12:00', 12.00, 12.00, 1, 12, '2024-11-24 03:57:21', '2024-11-24 03:57:21'),
	(7, 'Damian Reese', 'Veniam distinctio', '2024-09-01', '20:57:00', 511.00, 649.00, 1, 4, '2024-11-24 03:59:24', '2024-11-24 04:21:01'),
	(8, 'Rigel Robertson', 'In quod nostrud vel', '2009-08-23', '23:09:00', 555.00, 258.00, 0, 13, '2024-11-24 04:02:02', '2024-11-24 04:02:02'),
	(9, 'Burton Webb', 'Labore do exercitati', '1994-07-20', '00:29:00', 170.00, 497.00, 1, 45, '2024-11-24 04:02:53', '2024-11-24 04:02:53'),
	(10, 'Nola Pierce', 'Elit excepturi mole', '1982-10-08', '23:10:00', 556.00, 417.00, 1, 68, '2024-11-24 04:03:05', '2024-11-24 04:21:32'),
	(11, 'Burke Reese', 'Qui sapiente ipsum', '1990-05-24', '18:59:00', 237.00, 754.00, 1, 49, '2024-11-24 04:23:40', '2024-11-24 04:23:40');

-- Dumping structure for table mellodian.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `run_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table mellodian.migrations: ~0 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `run_at`) VALUES
	(1, '01_create_users_table', '2024-11-23 08:45:56'),
	(2, '02_create_events_table', '2024-11-23 08:45:56'),
	(3, '03_create_tickets_table', '2024-11-23 08:45:56'),
	(4, '04_create_sales_table', '2024-11-23 08:45:56');

-- Dumping structure for table mellodian.sales
CREATE TABLE IF NOT EXISTS `sales` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` int NOT NULL,
  `event_id` int NOT NULL,
  `total_tickets_sold` int DEFAULT '0',
  `total_revenue` decimal(10,2) DEFAULT '0.00',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  KEY `ticket_id` (`ticket_id`),
  CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table mellodian.sales: ~4 rows (approximately)
INSERT INTO `sales` (`id`, `ticket_id`, `event_id`, `total_tickets_sold`, `total_revenue`, `updated_at`) VALUES
	(11, 24, 5, 1, 9.00, '2024-11-26 07:55:59');

-- Dumping structure for table mellodian.tickets
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `event_id` int NOT NULL,
  `seat_type` enum('with_table','without_table') COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int NOT NULL,
  `adult_photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `booking_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table mellodian.tickets: ~4 rows (approximately)
INSERT INTO `tickets` (`id`, `user_id`, `event_id`, `seat_type`, `quantity`, `adult_photo`, `total_price`, `booking_date`, `created_at`, `updated_at`) VALUES
	(24, 4, 5, 'without_table', 1, 'storage/adult_photo/photo_6745d36f3f5f55.96736296.png', 9.00, '2024-11-27 18:00:00', '2024-11-26 07:55:59', '2024-11-26 07:55:59');

-- Dumping structure for table mellodian.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `profile_photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table mellodian.users: ~2 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `profile_photo`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'admin@gmail.com', '01614341073', '$2y$10$QoVkJZLU8VIf5EMrnptqZOUKegNegVc5sr03p1LFcqM95gDeMAmEe', 'admin', 'storage/photos/photo_6741a7e1e2d1f0.19150945.png', '2024-11-23 04:01:06', '2024-11-23 10:01:29'),
	(4, 'User', 'user@gmail.com', '01788909090', '$2y$10$z9/rOZ6EuCIHyOx.JbNN5uIaw45/KfGm0Y6ojh99zn8z21oq8QEfK', 'user', 'storage/photos/photo_67444b6e168027.13075801.jpg', '2024-11-24 00:51:32', '2024-11-25 04:03:26'),
	(6, 'milon', 'jipasuba@mailinator.com', '01914341073', '$2y$10$ncqVFUJ2mX5d.Ak0COG9seoAcpWWKNOr07xjNOuaM1aRg7jS91UR2', 'user', 'storage/photos/photo_6744c819503141.51066551.png', '2024-11-25 05:44:59', '2024-11-25 12:55:21');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
