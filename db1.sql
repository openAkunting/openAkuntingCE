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

-- Dumping structure for table open_akunting.account
CREATE TABLE IF NOT EXISTS `account` (
  `id` varchar(50) NOT NULL DEFAULT '',
  `parentId` varchar(50) DEFAULT '',
  `name` varchar(250) NOT NULL DEFAULT '',
  `accountTypeId` varchar(10) NOT NULL DEFAULT '',
  `nature` varchar(1) NOT NULL DEFAULT '',
  `balance` double NOT NULL DEFAULT 0,
  `cashBank` int(1) NOT NULL DEFAULT 1,
  `status` varchar(2) NOT NULL DEFAULT '1',
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `inputBy` varchar(50) NOT NULL DEFAULT '',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateBy` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.account: ~13 rows (approximately)
INSERT INTO `account` (`id`, `parentId`, `name`, `accountTypeId`, `nature`, `balance`, `cashBank`, `status`, `presence`, `inputDate`, `inputBy`, `updateDate`, `updateBy`) VALUES
	('1', '0', '', '', '', 0, 1, '1', 1, '2024-01-01 00:00:00', '', '2024-01-01 00:00:00', ''),
	('10', '1', 'Kas', '100', 'D', 0, 0, '1', 1, '2024-01-01 00:00:00', '', '2024-02-10 06:40:21', '1a'),
	('10.1', '10', 'Kas Bank ', '100', 'C', 0, 0, '1', 1, '2024-01-01 00:00:00', '', '2024-02-10 06:40:21', '1a'),
	('10.2', '10', 'Piutang Usaha', '100', 'D', 0, 0, '1', 1, '2024-01-01 00:00:00', '', '2024-02-10 06:40:21', '1a'),
	('10.3', '10', 'Kas Bank BCA', '100', '', 0, 1, '1', 1, '2024-02-09 16:55:03', '1a', '2024-02-10 06:40:21', '1a'),
	('20', '1', 'Modal', '102', 'D', 0, 0, '1', 1, '2024-01-01 00:00:00', '', '2024-02-10 06:40:21', '1a'),
	('20.0', '20', 'Pendapatan Jasa', '101', '', 0, 1, '1', 1, '2024-02-09 16:53:57', '1a', '2024-02-10 06:40:21', '1a'),
	('20.0.100', '20.0', 'Pendapatan Jasa', '101', '', 0, 1, '1', 1, '2024-02-09 16:54:21', '1a', '2024-02-10 06:40:21', '1a'),
	('20.1', '20', 'Pendapatan', '12', 'C', 0, 0, '1', 1, '2024-01-01 00:00:00', '', '2024-02-10 06:40:21', '1a'),
	('20.2', '20', 'Pendapatan Jasa 2', '101', '', 0, 1, '1', 1, '2024-02-09 16:54:35', '1a', '2024-02-10 06:40:21', '1a'),
	('4000', '1', 'JPB', '100', '', 0, 1, '1', 1, '2024-02-09 17:10:19', '1a', '2024-02-10 06:40:21', '1a'),
	('4000.100', '4000', 'JPB 1', '101', '', 0, 1, '1', 1, '2024-02-09 17:10:30', '1a', '2024-02-10 06:40:21', '1a'),
	('4000.101', '4000', 'JPB 2', '100', '', 133333220, 1, '1', 1, '2024-02-09 17:10:47', '1a', '2024-02-10 06:40:21', '1a');

-- Dumping structure for table open_akunting.account_type
CREATE TABLE IF NOT EXISTS `account_type` (
  `id` varchar(10) NOT NULL DEFAULT '0',
  `name` varchar(250) NOT NULL DEFAULT '',
  `normalBalance` varchar(1) NOT NULL DEFAULT 'D',
  `position` varchar(5) NOT NULL DEFAULT '',
  `status` varchar(2) NOT NULL DEFAULT '1',
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `inputBy` varchar(50) NOT NULL DEFAULT '',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateBy` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.account_type: ~7 rows (approximately)
INSERT INTO `account_type` (`id`, `name`, `normalBalance`, `position`, `status`, `presence`, `inputDate`, `inputBy`, `updateDate`, `updateBy`) VALUES
	('100', 'Aset123', 'D', 'BS', '1', 1, '2024-01-01 00:00:00', '', '2024-02-10 08:18:40', '1a'),
	('101', 'Kewajiban   ', 'C', 'BS', '1', 1, '2024-01-01 00:00:00', '', '2024-02-10 08:18:40', '1a'),
	('102', 'Ekuitas     ', 'C', 'BS', '1', 1, '2024-01-01 00:00:00', '', '2024-02-10 08:18:40', '1a'),
	('103', 'Beban       ', 'D', 'BS', '1', 1, '2024-01-01 00:00:00', '', '2024-02-10 08:18:40', '1a'),
	('12', 'Pendapatan', 'C', 'PL', '1', 0, '2024-01-01 00:00:00', '', '2024-02-10 08:42:29', '1a'),
	('300', 'Hutang', 'C', 'PL', '1', 1, '2024-02-10 08:36:48', '1a', '2024-02-10 08:36:48', '1a'),
	('400', 'Piutang', 'C', 'PL', '1', 1, '2024-02-10 08:40:44', '1a', '2024-02-10 08:40:44', '1a');

-- Dumping structure for table open_akunting.auto_number
CREATE TABLE IF NOT EXISTS `auto_number` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `prefix` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `digit` int(11) NOT NULL DEFAULT 6,
  `runningNumber` int(11) NOT NULL DEFAULT 0,
  `lastRecord` varchar(50) DEFAULT NULL,
  `updateDate` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=306 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table open_akunting.auto_number: ~0 rows (approximately)
INSERT INTO `auto_number` (`id`, `name`, `prefix`, `digit`, `runningNumber`, `lastRecord`, `updateDate`) VALUES
	(305, 'account', 'A', 6, 6, 'A000006', 2024);

-- Dumping structure for table open_akunting.global_parameter
CREATE TABLE IF NOT EXISTS `global_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL DEFAULT '',
  `value` varchar(250) NOT NULL DEFAULT '',
  `description` text NOT NULL DEFAULT '',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateBy` varchar(50) NOT NULL DEFAULT '',
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `inputBy` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.global_parameter: ~2 rows (approximately)
INSERT INTO `global_parameter` (`id`, `name`, `value`, `description`, `updateDate`, `updateBy`, `inputDate`, `inputBy`) VALUES
	(1, 'Company  Name', 'Company Name', '', '2024-02-08 10:45:51', '', '2024-01-01 00:00:00', ''),
	(2, 'Company Address', 'Company Address, 	Company Address', '', '2024-02-08 10:45:51', '', '2024-01-01 00:00:00', '');

-- Dumping structure for table open_akunting.journal
CREATE TABLE IF NOT EXISTS `journal` (
  `id` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `refId` varchar(50) NOT NULL DEFAULT '',
  `debit` double NOT NULL DEFAULT 0,
  `credit` double NOT NULL DEFAULT 0,
  `presence` int(11) NOT NULL DEFAULT 1,
  `tenantId` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table open_akunting.journal: ~4 rows (approximately)
INSERT INTO `journal` (`id`, `name`, `refId`, `debit`, `credit`, `presence`, `tenantId`) VALUES
	('1', 'abc', '12', 14, 125, 1, 't2'),
	('11', '2312', '12', 14, 125, 1, 't2'),
	('12', 'accc', '12', 14, 125, 1, 't2'),
	('2', 'vcs', '124', 332, 0, 1, '2');

-- Dumping structure for table open_akunting.language
CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL DEFAULT '0',
  `lang1` text NOT NULL,
  `lang2` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.language: ~0 rows (approximately)

-- Dumping structure for table open_akunting.module
CREATE TABLE IF NOT EXISTS `module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2002 DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.module: ~5 rows (approximately)
INSERT INTO `module` (`id`, `name`, `description`) VALUES
	(110, 'Global Parameter', NULL),
	(120, 'Master Number', NULL),
	(130, 'Language', NULL),
	(1000, 'User', NULL),
	(2000, 'Chart Of Account', NULL);

-- Dumping structure for table open_akunting.tenant
CREATE TABLE IF NOT EXISTS `tenant` (
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

-- Dumping data for table open_akunting.tenant: ~2 rows (approximately)
INSERT INTO `tenant` (`id`, `email`, `company`, `name`, `status`, `presence`, `inputDate`, `updateDate`) VALUES
	('t1', 'admin@localhost.com', 'Demo INC', 'demo 1', '1', 1, '2024-01-01 00:00:00', '2024-01-01 00:00:00'),
	('t2', 'admin@your_domain.com', 'your INC', 'demo 2', '1', 1, '2024-01-01 00:00:00', '2024-01-01 00:00:00');

-- Dumping structure for table open_akunting.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` varchar(50) NOT NULL DEFAULT '',
  `userRuleId` int(1) NOT NULL DEFAULT 0,
  `email` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `priority` int(1) NOT NULL DEFAULT 0,
  `sa` int(1) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 1,
  `presence` int(2) NOT NULL DEFAULT 1,
  `inputBy` varchar(50) NOT NULL DEFAULT '',
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateBy` varchar(50) NOT NULL DEFAULT '',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.user: ~2 rows (approximately)
INSERT INTO `user` (`id`, `userRuleId`, `email`, `name`, `password`, `priority`, `sa`, `status`, `presence`, `inputBy`, `inputDate`, `updateBy`, `updateDate`) VALUES
	('1a', 1, 'admin@localhost.com', 'Woo', '4297f44b13955235245b2497399d7a93', 1, 1, 1, 1, '', '2024-01-01 00:00:00', '1a', '2024-02-10 16:55:57'),
	('201', 1, 'user201@demo.com', 'Olive 123', '4297f44b13955235245b2497399d7a93', 0, 0, 1, 1, '', '2024-01-01 00:00:00', '1a', '2024-02-10 16:55:46');

-- Dumping structure for table open_akunting.user_jti
CREATE TABLE IF NOT EXISTS `user_jti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `jti` varchar(50) NOT NULL DEFAULT '',
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table open_akunting.user_jti: ~0 rows (approximately)

-- Dumping structure for table open_akunting.user_otp
CREATE TABLE IF NOT EXISTS `user_otp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accountId` varchar(50) NOT NULL,
  `requestCode` varchar(50) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table open_akunting.user_otp: ~2 rows (approximately)
INSERT INTO `user_otp` (`id`, `accountId`, `requestCode`, `presence`, `inputDate`, `updateDate`) VALUES
	(1, '201', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9', 0, '2024-01-01 00:00:00', '2024-01-19 19:14:42'),
	(2, 't', 'Singtel Innov8 は現在、2 億 5,000 万米ドルもの資本を所有しており、シンガポー', 1, '2024-01-01 00:00:00', '2024-01-01 00:00:00');

-- Dumping structure for table open_akunting.user_role
CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `isLock` int(1) NOT NULL DEFAULT 0,
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `inputBy` varchar(50) NOT NULL DEFAULT '',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateBy` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.user_role: ~2 rows (approximately)
INSERT INTO `user_role` (`id`, `name`, `isLock`, `presence`, `inputDate`, `inputBy`, `updateDate`, `updateBy`) VALUES
	(1, 'Admin', 1, 1, '2024-01-01 00:00:00', '', '2024-01-01 00:00:00', ''),
	(10, 'User', 1, 1, '2024-01-01 00:00:00', '', '2024-01-01 00:00:00', '');

-- Dumping structure for table open_akunting.user_role_access
CREATE TABLE IF NOT EXISTS `user_role_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userRulesId` int(11) NOT NULL DEFAULT 0,
  `moduleId` int(11) NOT NULL DEFAULT 0,
  `_read` varchar(1) NOT NULL DEFAULT '1',
  `_create` varchar(1) NOT NULL DEFAULT '1',
  `_update` varchar(1) NOT NULL DEFAULT '1',
  `_delete` varchar(1) NOT NULL DEFAULT '1',
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `inputBy` varchar(50) NOT NULL DEFAULT '',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateBy` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.user_role_access: ~5 rows (approximately)
INSERT INTO `user_role_access` (`id`, `userRulesId`, `moduleId`, `_read`, `_create`, `_update`, `_delete`, `presence`, `inputDate`, `inputBy`, `updateDate`, `updateBy`) VALUES
	(1, 1, 2000, '1', '1', '1', '1', 1, '2024-01-01 00:00:00', '', '2024-02-10 18:18:44', '1a'),
	(17, 1, 110, '1', '1', '1', '0', 1, '2024-02-10 18:22:45', '1a', '2024-02-10 18:22:45', '1a'),
	(18, 1, 120, '1', '1', '1', '0', 1, '2024-02-10 18:22:45', '1a', '2024-02-10 18:22:45', '1a'),
	(19, 1, 130, '1', '1', '1', '0', 1, '2024-02-10 18:22:45', '1a', '2024-02-10 18:22:45', '1a'),
	(20, 1, 1000, '1', '1', '1', '0', 1, '2024-02-10 18:22:45', '1a', '2024-02-10 18:22:45', '1a'),
	(21, 10, 110, '1', '1', '1', '0', 1, '2024-02-10 18:25:30', '1a', '2024-02-10 18:25:30', '1a'),
	(22, 10, 120, '1', '1', '1', '0', 1, '2024-02-10 18:25:30', '1a', '2024-02-10 18:25:30', '1a'),
	(23, 10, 130, '1', '1', '1', '0', 1, '2024-02-10 18:25:30', '1a', '2024-02-10 18:25:30', '1a'),
	(24, 10, 1000, '1', '1', '1', '0', 1, '2024-02-10 18:25:30', '1a', '2024-02-10 18:25:30', '1a'),
	(25, 10, 2000, '1', '1', '1', '0', 1, '2024-02-10 18:25:30', '1a', '2024-02-10 18:25:30', '1a');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
