# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.35)
# Database: scandb
# Generation Time: 2017-05-23 15:14:54 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table customers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comanyname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;

INSERT INTO `customers` (`id`, `name`, `comanyname`, `date`, `created_at`, `updated_at`)
VALUES
	(1,'ruben','rubenbv','2017-01-01','2017-01-01 00:00:00','2017-01-01 00:00:00');

/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table headers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `headers`;

CREATE TABLE `headers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `headers_website_id_foreign` (`website_id`),
  CONSTRAINT `headers_website_id_foreign` FOREIGN KEY (`website_id`) REFERENCES `websites` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `headers` WRITE;
/*!40000 ALTER TABLE `headers` DISABLE KEYS */;

INSERT INTO `headers` (`id`, `name`, `value`, `website_id`, `created_at`, `updated_at`)
VALUES
	(592,'Date','Tue, 23 May 2017 15:11:48 GMT',62,'2017-05-23 15:11:48','2017-05-23 15:11:48'),
	(593,'Content-Type','text/html;charset=utf-8',62,'2017-05-23 15:11:48','2017-05-23 15:11:48'),
	(594,'Transfer-Encoding','chunked',62,'2017-05-23 15:11:48','2017-05-23 15:11:48'),
	(595,'Connection','keep-alive',62,'2017-05-23 15:11:48','2017-05-23 15:11:48'),
	(596,'Vary','Accept-Encoding',62,'2017-05-23 15:11:48','2017-05-23 15:11:48'),
	(597,'Expires','Tue, 23 May 2017 15:11:49 GMT',62,'2017-05-23 15:11:48','2017-05-23 15:11:48'),
	(598,'Link','<https://justbetter.nl/>; rel=shortlink',62,'2017-05-23 15:11:48','2017-05-23 15:11:48'),
	(599,'Last-Modified','Mon, 24 Apr 2017 06:42:22 GMT',62,'2017-05-23 15:11:48','2017-05-23 15:11:48'),
	(600,'Server','cloudflare-nginx',62,'2017-05-23 15:11:48','2017-05-23 15:11:48'),
	(601,'CF-RAY','3638f32a1b3d2c66-AMS',62,'2017-05-23 15:11:48','2017-05-23 15:11:48');

/*!40000 ALTER TABLE `headers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table links
# ------------------------------------------------------------

DROP TABLE IF EXISTS `links`;

CREATE TABLE `links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `methode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `links_website_id_foreign` (`website_id`),
  CONSTRAINT `links_website_id_foreign` FOREIGN KEY (`website_id`) REFERENCES `websites` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `links` WRITE;
/*!40000 ALTER TABLE `links` DISABLE KEYS */;

INSERT INTO `links` (`id`, `methode`, `url`, `website_id`, `created_at`, `updated_at`)
VALUES
	(856,'GET','https://justbetter.nl',62,'2017-05-23 15:11:49','2017-05-23 15:11:49'),
	(857,'GET','https://justbetter.nl/expertise/',62,'2017-05-23 15:11:49','2017-05-23 15:11:49'),
	(858,'GET','https://justbetter.nl/cases/',62,'2017-05-23 15:11:49','2017-05-23 15:11:49'),
	(859,'GET','https://justbetter.nl/klanten/',62,'2017-05-23 15:11:49','2017-05-23 15:11:49'),
	(860,'GET','https://justbetter.nl/over-ons/',62,'2017-05-23 15:11:49','2017-05-23 15:11:49'),
	(861,'GET','https://justbetter.nl/vacatures/',62,'2017-05-23 15:11:49','2017-05-23 15:11:49'),
	(862,'GET','https://justbetter.nl/contact/',62,'2017-05-23 15:11:49','2017-05-23 15:11:49'),
	(863,'GET','https://www.facebook.com/justbetteronline/',62,'2017-05-23 15:11:49','2017-05-23 15:11:49'),
	(864,'GET','https://www.linkedin.com/company/justbetter',62,'2017-05-23 15:11:49','2017-05-23 15:11:49'),
	(865,'GET','https://github.com/just-better',62,'2017-05-23 15:11:49','2017-05-23 15:11:49'),
	(866,'GET','https://justbetter.nl/',62,'2017-05-23 15:11:49','2017-05-23 15:11:49'),
	(867,'GET','https://justbetter.nl/feed/',62,'2017-05-23 15:11:49','2017-05-23 15:11:49'),
	(868,'GET','https://justbetter.nl/comments/feed/',62,'2017-05-23 15:11:49','2017-05-23 15:11:49'),
	(869,'GET','https://fonts.googleapis.com/css?family=Open+Sans%3A400%2C300%2C400italic%2C700%7CHind%3A500%2C300%2C400&subset=latin&ver=1493038815',62,'2017-05-23 15:11:49','2017-05-23 15:11:49'),
	(870,'GET','https://justbetter.nl/wp-json/',62,'2017-05-23 15:11:49','2017-05-23 15:11:49'),
	(871,'GET','https://justbetter.nl/wp/xmlrpc.php?rsd',62,'2017-05-23 15:11:49','2017-05-23 15:11:49'),
	(872,'GET','https://justbetter.nl/wp-json/oembed/1.0/embed?url=https%3A%2F%2Fjustbetter.nl%2F',62,'2017-05-23 15:11:49','2017-05-23 15:11:49'),
	(873,'GET','https://justbetter.nl/wp-json/oembed/1.0/embed?url=https%3A%2F%2Fjustbetter.nl%2F&format=xml',62,'2017-05-23 15:11:49','2017-05-23 15:11:49');

/*!40000 ALTER TABLE `links` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'2017_05_17_071500_create_customers_table',1),
	(2,'2017_05_17_071540_create_websites_table',1),
	(3,'2017_05_17_071600_create_links_table',1),
	(4,'2017_05_17_071708_create_rapports_table',1),
	(5,'2017_05_17_071739_create_scans_table',1),
	(6,'2017_05_17_071900_create_headers_table',1),
	(7,'2017_05_17_113356_create_scan_details_table',1),
	(8,'2017_05_19_105046_create_modules_table',1);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table modules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `modules`;

CREATE TABLE `modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scan_id` int(10) unsigned NOT NULL,
  `sql` tinyint(1) NOT NULL DEFAULT '0',
  `xss` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `modules_scan_id_foreign` (`scan_id`),
  CONSTRAINT `modules_scan_id_foreign` FOREIGN KEY (`scan_id`) REFERENCES `scans` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table rapports
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rapports`;

CREATE TABLE `rapports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `website_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rapports_website_id_foreign` (`website_id`),
  CONSTRAINT `rapports_website_id_foreign` FOREIGN KEY (`website_id`) REFERENCES `websites` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table scan_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `scan_details`;

CREATE TABLE `scan_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scan_id` int(10) unsigned NOT NULL,
  `f_scan_key` int(10) unsigned NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sql_inj` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thread` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thread_level` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scan_details_scan_id_foreign` (`scan_id`),
  KEY `scan_details_f_scan_key_foreign` (`f_scan_key`),
  CONSTRAINT `scan_details_f_scan_key_foreign` FOREIGN KEY (`f_scan_key`) REFERENCES `scans` (`scan_key`),
  CONSTRAINT `scan_details_scan_id_foreign` FOREIGN KEY (`scan_id`) REFERENCES `scans` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table scans
# ------------------------------------------------------------

DROP TABLE IF EXISTS `scans`;

CREATE TABLE `scans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `website_id` int(10) unsigned NOT NULL,
  `scan_key` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `scans_scan_key_unique` (`scan_key`),
  KEY `scans_website_id_foreign` (`website_id`),
  CONSTRAINT `scans_website_id_foreign` FOREIGN KEY (`website_id`) REFERENCES `websites` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table websites
# ------------------------------------------------------------

DROP TABLE IF EXISTS `websites`;

CREATE TABLE `websites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `base_url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `server` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `websites_customer_id_foreign` (`customer_id`),
  CONSTRAINT `websites_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `websites` WRITE;
/*!40000 ALTER TABLE `websites` DISABLE KEYS */;

INSERT INTO `websites` (`id`, `base_url`, `server`, `date`, `customer_id`, `created_at`, `updated_at`)
VALUES
	(62,'https://www.justbetter.nl','cloudflare-nginx','2017-05-23',1,'2017-05-23 15:11:48','2017-05-23 15:11:48');

/*!40000 ALTER TABLE `websites` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
