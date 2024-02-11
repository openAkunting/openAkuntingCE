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
) ENGINE=InnoDB AUTO_INCREMENT=2003 DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

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
  `jti` varchar(50) NOT NULL DEFAULT '',
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table open_akunting.user_jti: ~7 rows (approximately)
INSERT INTO `user_jti` (`id`, `userId`, `jti`, `inputDate`) VALUES
	(59, '1a', '86513847db05f636ce8ac3275b58108e', '2024-02-11 15:39:53'),
	(60, '1a', '00b1921921983eb5cb2b02e5589a438d', '2024-02-11 16:17:59'),
	(61, '1a', '4654aab75902efe26cd788ccca624ac1', '2024-02-11 16:20:11'),
	(62, '1a', '9d55f8fe0de35956aecf4a157e78e701', '2024-02-11 16:20:11'),
	(63, '1a', '8881377971af02598a2cdbdbaade054e', '2024-02-11 16:33:34'),
	(64, '1a', '9b5ca5368cc69495a563e3e2ffee21ff', '2024-02-11 16:33:34'),
	(65, '1a', '428e074690e16cc661bbe929979b1d9e', '2024-02-11 16:33:34'),
	(66, '1a', 'b701288b27ff3bbe2804befffd42304a', '2024-02-11 17:14:12'),
	(67, '1a', '0a15516982acff950197bf69f3d797a3', '2024-02-11 17:14:12'),
	(68, '1a', '71dbfa96f5c39bc5d6e6e1ab7192c917', '2024-02-11 17:14:12'),
	(69, '1a', 'b77fc40893b49d0972c54b71eaed6248', '2024-02-11 17:14:47'),
	(70, '1a', '4ef97facb227fbf97f709e728a9e7fc9', '2024-02-11 17:14:47'),
	(71, '1a', 'a8cd51cc045bc00882d3b666b7182bc4', '2024-02-11 17:14:47'),
	(72, '1a', 'b54a31c7f3b71c06e81cebf31b3aa935', '2024-02-11 17:15:06'),
	(73, '1a', 'e64a1c139649df5947011906373ba79b', '2024-02-11 17:15:06'),
	(74, '1a', '7d828a7186e007d60d6b30821aaade7c', '2024-02-11 17:15:06'),
	(75, '1a', 'ff2c9b571d9342c43ded4730b767dc7e', '2024-02-11 17:15:49'),
	(76, '1a', '45207a70cdc4b28dec2c64c9e4bdfd36', '2024-02-11 17:15:49'),
	(77, '1a', '21782e70d911ab19ee0f5c322854fa4b', '2024-02-11 17:15:49'),
	(78, '1a', 'e24ea995beb5f88c4854f60c2b19993a', '2024-02-11 17:16:23'),
	(79, '1a', '5910d20d79a9430da3776259c11c9f64', '2024-02-11 17:16:23'),
	(80, '1a', 'a2abb772ced62be137248abf09948bf1', '2024-02-11 17:16:42'),
	(81, '1a', 'a792f0977f6923b2701021c1bc43bc31', '2024-02-11 17:16:42'),
	(82, '1a', '4fe9fdb7a608e74913b727a76f973675', '2024-02-11 17:17:31'),
	(83, '1a', 'eee28f6ca8b0d029e4c268a21cbd242a', '2024-02-11 17:17:31'),
	(84, '1a', 'fb044841e8336e6dd68da85a111f7ffb', '2024-02-11 17:18:00'),
	(85, '1a', 'c29ee77604463b1a5a6311d6782f07af', '2024-02-11 17:18:00'),
	(86, '1a', 'd628b43aef342487f1d84970415f5475', '2024-02-11 17:18:00'),
	(87, '1a', '3c9ed06c3949e06b7c716adcf86c3697', '2024-02-11 17:18:40'),
	(88, '1a', 'f926657dd7c81eee8cf4f1b2293c29ed', '2024-02-11 17:18:40'),
	(89, '1a', '3362f0cf7a7bb7703b28574271602075', '2024-02-11 17:18:59'),
	(90, '1a', '5e91d313dbcd843e0ef0780401f87658', '2024-02-11 17:18:59'),
	(91, '1a', 'dc0c87d659fbe6b104c9b3ee33fa604c', '2024-02-11 17:19:32'),
	(92, '1a', 'e9c8876fb2d96523438ae5c334a54b8d', '2024-02-11 17:19:32'),
	(93, '1a', '866ddb784a0823f614df234a7f08170f', '2024-02-11 17:19:44'),
	(94, '1a', 'e2a8f17236d9e48d10f9d3103f840c50', '2024-02-11 17:19:44'),
	(95, '1a', 'e7b4b55ae6303972f023de51c3fb2938', '2024-02-11 17:19:44'),
	(96, '1a', '4e64b0f8fe4fde6f46c5b9a0544505ac', '2024-02-11 17:19:52'),
	(97, '1a', '50b31f460f0c19c59bdec008163d23a2', '2024-02-11 17:19:52'),
	(98, '1a', '7bc0639610879cffd2dad51cb8502a3b', '2024-02-11 17:19:57'),
	(99, '1a', '51ea952436c76a8f8c926c070395f93a', '2024-02-11 17:19:57'),
	(100, '1a', '1eecf32b9ed2fe76e0228c18ec3e57ac', '2024-02-11 17:19:57'),
	(101, '1a', 'edef65f231d370a0d1677f38070f0178', '2024-02-11 17:20:05'),
	(102, '1a', '859a0e219b64a71f8a8e6126db95e162', '2024-02-11 17:20:05'),
	(103, '1a', '027853100aeab0444af89fdcf157edba', '2024-02-11 17:20:05'),
	(104, '1a', '7b79e33f79c0dab87d1b70ea2a5ec821', '2024-02-11 17:23:34'),
	(105, '1a', '43543d38c2d082f6ea5db627839b763c', '2024-02-11 17:23:34'),
	(106, '1a', 'c8fbd2c5883aadf333265d79a81e7173', '2024-02-11 17:23:34');

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
  `_create` varchar(1) NOT NULL DEFAULT '1',
  `_read` varchar(1) NOT NULL DEFAULT '1',
  `_update` varchar(1) NOT NULL DEFAULT '1',
  `_delete` varchar(1) NOT NULL DEFAULT '1',
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `inputBy` varchar(50) NOT NULL DEFAULT '',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateBy` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.user_role_access: ~10 rows (approximately)
INSERT INTO `user_role_access` (`id`, `userRulesId`, `moduleId`, `_create`, `_read`, `_update`, `_delete`, `presence`, `inputDate`, `inputBy`, `updateDate`, `updateBy`) VALUES
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
