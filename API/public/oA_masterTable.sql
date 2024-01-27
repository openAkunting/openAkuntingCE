-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               10.4.28-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table open_akunting.a1_account
CREATE TABLE IF NOT EXISTS `a1_account` (
  `id` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `priority` int(1) NOT NULL DEFAULT 0,
  `presence` int(2) NOT NULL DEFAULT 1,
  `tenantId` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.a1_account: ~3 rows (approximately)
INSERT INTO `a1_account` (`id`, `email`, `name`, `password`, `priority`, `presence`, `tenantId`) VALUES
	('1a', 'admin@localhost.com', 'Woo', '4297f44b13955235245b2497399d7a93', 1, 1, 't1'),
	('1b', 'admin@localhost.com', 'John', '4297f44b13955235245b2497399d7a93', 0, 1, 't2'),
	('201', 'user201@demo.com', 'Olive', '4297f44b13955235245b2497399d7a93', 0, 1, '');

-- Dumping structure for table open_akunting.a1_account_jti
CREATE TABLE IF NOT EXISTS `a1_account_jti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL DEFAULT '',
  `jti` varchar(50) NOT NULL DEFAULT '',
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table open_akunting.a1_account_jti: ~4 rows (approximately)
INSERT INTO `a1_account_jti` (`id`, `email`, `jti`, `inputDate`) VALUES
	(1, 'admin@localhost.com', '751dc1cd4976dd6395cac7ba8582c9c5', '2024-01-01 00:00:00'),
	(3, 'admin@localhost.com', 'f81c5bfdd12327e3a4040350162569f2', '2024-01-27 07:52:19'),
	(4, 'admin@localhost.com', '2bcc5bfc53e54023f622799fc0125930', '2024-01-27 07:52:26'),
	(5, 'admin@localhost.com', '9dea1e822dc755f3c33cc40d5e6b9ffb', '2024-01-27 07:53:17');

-- Dumping structure for table open_akunting.a1_account_otp
CREATE TABLE IF NOT EXISTS `a1_account_otp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accountId` varchar(50) NOT NULL,
  `requestCode` varchar(50) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table open_akunting.a1_account_otp: ~2 rows (approximately)
INSERT INTO `a1_account_otp` (`id`, `accountId`, `requestCode`, `presence`, `inputDate`, `updateDate`) VALUES
	(1, '201', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9', 0, '2024-01-01 00:00:00', '2024-01-19 19:14:42'),
	(2, 't', 'Singtel Innov8 は現在、2 億 5,000 万米ドルもの資本を所有しており、シンガポー', 1, '2024-01-01 00:00:00', '2024-01-01 00:00:00');

-- Dumping structure for table open_akunting.a1_journal
CREATE TABLE IF NOT EXISTS `a1_journal` (
  `id` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `refId` varchar(50) NOT NULL DEFAULT '',
  `debit` double NOT NULL DEFAULT 0,
  `credit` double NOT NULL DEFAULT 0,
  `presence` int(11) NOT NULL DEFAULT 1,
  `tenantId` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table open_akunting.a1_journal: ~4 rows (approximately)
INSERT INTO `a1_journal` (`id`, `name`, `refId`, `debit`, `credit`, `presence`, `tenantId`) VALUES
	('1', 'abc', '12', 14, 125, 1, 't2'),
	('11', '2312', '12', 14, 125, 1, 't2'),
	('12', 'accc', '12', 14, 125, 1, 't2'),
	('2', 'vcs', '124', 332, 0, 1, '2');

-- Dumping structure for table open_akunting.a1_tenant
CREATE TABLE IF NOT EXISTS `a1_tenant` (
  `id` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `company` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `status` varchar(50) NOT NULL DEFAULT '',
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.a1_tenant: ~2 rows (approximately)
INSERT INTO `a1_tenant` (`id`, `email`, `company`, `name`, `status`, `presence`, `inputDate`, `updateDate`) VALUES
	('t1', 'admin@localhost.com', 'Demo INC', 'demo 1', '1', 1, '2024-01-01 00:00:00', '2024-01-01 00:00:00'),
	('t2', 'admin@your_domain.com', 'your INC', 'demo 2', '1', 1, '2024-01-01 00:00:00', '2024-01-01 00:00:00');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
