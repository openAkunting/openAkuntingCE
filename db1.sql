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
  `cashBank` int(1) NOT NULL DEFAULT 1,
  `status` varchar(2) NOT NULL DEFAULT '1',
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `inputBy` varchar(50) NOT NULL DEFAULT '',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateBy` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.account: ~170 rows (approximately)
INSERT INTO `account` (`id`, `parentId`, `name`, `accountTypeId`, `cashBank`, `status`, `presence`, `inputDate`, `inputBy`, `updateDate`, `updateBy`) VALUES
	('1', '0', 'ASSETS', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-01-01 00:00:00', ''),
	('11', '1', 'CURRENT ASSET', '100', 0, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1110', '11', 'CASH AND BANK', '100', 0, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1110.001.000', '1110', 'Petty Cash', '100', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1110.003.000', '1110', 'Advance - Others', '100', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1110.005.000', '1110', 'BCA In - KGM3', '100', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1110.007.000', '1110', 'Danamon Out', '100', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1110.009.000', '1110', 'Mandiri - KGM3', '100', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1110.011.000', '1110', 'BCA Out - MKG3', '100', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1110.015.000', '1110', '', '100', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1110.020.000', '1110', 'Mandiri In', '100', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1110.021.000', '1110', 'ARTHA GRAHA BANK', '100', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1110.022.000', '1110', 'BRI', '100', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120', '11', 'ACCOUNT RECEIVABLE', '100', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.000.001', '1120', 'AR - Trade', '101', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.000.002', '1120', 'AR OTHER', '101', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.009.000', '1120', 'AR - Tjia Mei Liang', '101', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.010.604', '1120', 'AR RMM', '101', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.010.605', '1120', 'AR PT SELERA PRIMA LESTARINDO', '101', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.030.000', '1120', 'AR CASH', '101', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.090.001', '1120', 'AR - LKI Surabaya', '101', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.090.002', '1120', 'AR - LKI Bali', '101', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.090.003', '1120', 'AR - MMI', '101', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.091.001', '1120.010.000', 'AR BANK BCA V,M ', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.091.002', '1120.010.000', 'AR BANK BCA DEBIT', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.091.003', '1120.010.000', 'AR BANK BCA FLAZZ', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.091.004', '1120.010.000', 'AR BANK MANDIRI DEBIT,V,M', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.091.005', '1120.010.000', 'AR BANK DANAMON DEBIT,V,M', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.091.006', '1120.010.000', 'AR BANK CIMB DEBIT,V,M', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.091.007', '1120.010.000', 'AR BANK CITIBANK V,M', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.091.008', '1120.010.000', 'AR CASH', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1120.091.020', '1120', 'AR TENANT - ALL OUTLET', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1130', '11', 'INVENTORY', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1130.001.000', '1130', 'Inventory - Food', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1130.002.000', '1130', 'Inventory - Beverage', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1130.003.000', '1130', 'Inventory - Supplies', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1130.004.000', '1130', 'Inventory - Prosess Food', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1130.005.000', '1130', 'Inventory - Spicse', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1130.006.000', '1130', 'Inventory - Proses Spies', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1130.007.000', '1130', 'Inventory - Other', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1130.009.000', '1130', 'Inventory - Import Good', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1140', '11', 'PREPAID', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1140.001.000', '1140', 'Prepaid Rent', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1140.002.000', '1140', 'Prepaid Insurance', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1140.003.000', '1140', 'Prepaid Tax', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1140.004.000', '1140', 'Prepaid Service Charge', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1150.001.000', '1140', 'Down Payment', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1160', '11', 'FIX ASSET', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1160.001.000', '1160', 'FA - Motor Vehicle ', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1160.002.000', '1160', 'FA - Computer', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1160.003.000', '1160', 'FA - Furniture ', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1160.004.000', '1160', 'FA - Equipment', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1160.005.000', '1160', 'FA - Furniture Fix (Opr)', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1160.006.000', '1160', 'FA - Renovation', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1170', '11', 'ACCUMULATE DEPRETIATION', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1170.001.000', '1170', 'Acc. Depr. Motor Vehicle ', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1170.002.000', '1170', 'Acc. Depr. Computer', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1170.003.000', '1170', 'Acc. Depr. Furniture', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1170.004.000', '1170', 'Acc. Depr. Equipment', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1170.005.000', '1170', 'Acc. Depr. Furniture Fix (Opr) ', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1170.006.000', '1170', 'Acc. Depr. Renovation', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1180', '11', 'DEPOSIT', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1180.001.001', '1180', 'SECURITY DEPOSIT', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1181', '11', 'Advance', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1190', '11', 'LONG TERM INVESMENT', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1190-000.004', '1190', 'Selera Prima Lestarindo', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1190.000.001', '1190', 'Dinasti Kuliner', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1190.000.002', '1190', 'Boga Lima Radja Inti', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1190.000.003', '1190', 'Lima Radja Kuliner', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1190.000.004', '1190', 'Selera Prima Lestarindo', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1190.001.001', '1190', 'INVESTMENT', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1200', '1', 'OTHER ASSETS', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('1200.000.001', '1200', 'Pra Operating', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('200', '-', 'LEABILITIES & CAPITAL', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2000', '200', 'ACCOUNT PAYABLE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2110.000.001', '2000', 'AP - Trade', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2110.000.002', '2000', 'AP - Building', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2110.000.003', '2000', 'AP - Jamsostek', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2110.000.004', '2000', 'AP - FOODINDO', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2110.000.005', '2000', 'AP - TML', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2110.000.006', '2000', 'AP - AT', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2110.000.007', '2000', 'AP - ADT', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2110.000.010', '2000', 'AP OTHER', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2120', '200', 'TAX', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2120.000.001', '2120', 'Tax Payable PB1', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2120.000.002', '2120', 'Tax Payable PPh 21', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2120.000.003', '2120', 'Tax Payable PPh 23', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2120.000.004', '2120', 'Tax Payable PPh 25', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2120.000.005', '2120', 'Tax Payable PPh 4 (2)', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2120.000.006', '2120', 'Tax Payable PPh Ps 2 Ayat 2', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2120.000.007', '2120', 'Tax Payable PPN', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2130', '200', 'CUSTOMER DEPOSIT', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('2140', '200', 'SERVICE CHARGE MOKKA COFFE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('3110', '200', 'CAPITAL', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('3110.001.000', '3110', 'Return Earning', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('3110.002.000', '3110', 'Current Return Earning', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('3110.003.000', '3110', 'CURRENT PROFIT & LOSS', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('3110.004.000', '3110', 'Credit Note', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('3120', '3110', 'SHAREHOLDERS', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('3120.001.000', '3120', 'Shareholders - TML', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('3120.002.000', '3120', 'Shareholder - TF', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('3120.003.000', '3120', 'Shareholder - IT', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('3120.004.000', '3120', 'Shareholder - SSP', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('3120.005.000', '3120', 'Shareholder - AT', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('3120.006.000', '3120', 'Shareholder - ADT', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('4000', '-', 'SALES', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('4000.200', '4000', 'SALES MOKKA COFFE CABANA', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('4000.200.001', '4000.200', 'SALES MOKKA', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('4111.001', '4000', 'SALES MOKKA ', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('4111.001.001', '4111.001', 'MCB - Sales - Food', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('4111.001.002', '4111.001', 'MCB - Sales - Baverages', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('4111.001.003', '4111.001', 'MCB - Sales - Cake', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('4111.005.000', '4000', 'SALES IMPORT', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('4113.001', '4000', 'DISCOUNT', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('4113.002.001', '4113.001', 'Discount Mokka Bintaro', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('5110', '4000', 'COGS', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('5110.001', '5110', 'COGS MKG', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('5111.001', '', 'COGS MOKKA COFFE BINTARO', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('5111.001.001', '5111.001', 'MCB - COGS - Food', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('5111.001.002', '5111.001', 'MCB - COGS - Beverages', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6000', '-', 'EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110', '6000', 'OPERATIONAL EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.001', '6110', 'SALARY EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.002', '6110', 'JAMSOSTEK EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.003', '6110', 'SERVICE CHARGE DISTRIBUTION EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.004', '6110', 'MEDICAL EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.005', '6110', 'TRANSPORT EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.006', '6110', 'MARKETING AND PROMOTION EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.007', '6110', 'ENTERTAINMENT EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.008', '6110', 'SUPPLIES EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.009', '6110', 'TRAINING EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.010', '6110', 'PRODUCT DEVELOPMENT EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.011', '6110', 'VARIANCE EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.012', '6110', 'DELIVERY ORDER EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.013', '6110', 'LEGAL & PROFESIONAL FEE EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.014', '6110', 'BANK ADMIN EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.015', '6110', 'COPY & PRINT EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.016', '6110', 'MAINTENANCE EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.017', '6110', 'ELECTRICAL EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.019', '6110', 'WATER EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.020', '6110', 'GAS EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.021', '6110', 'TELEPHONE EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.022', '6110', 'INTERNET EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6110.000.023', '6110', 'ADMIN EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6111', '6000', 'ADMIN EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6120', '6000', 'DEP. EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6120.001.001', '6120', 'Depr. Expense Motor Vehicle', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6120.001.002', '6120', 'Depr. Expense Computer', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6120.001.003', '6120', 'Depr. Expense Office Furniture', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6120.001.004', '6120', 'Depr. Expense Bar Equip', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6120.001.005', '6120', 'Depr. Expense Furniture Fix (Opr)', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6120.001.006', '6120', 'Depr.Expense Pra Operating', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6120.001.007', '6120', 'Depr. Expense Renovation', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6200', '6000', 'RENTAL & SERVISE CHARGE EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6200.001-005', '6000', 'OTHER EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6200.001.001', '6200', 'BUILDING SERVICE CHARGE EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6200.001.002', '6200', 'BUILDING RENTAL EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6200.001.003', '6200', 'CENTER PROMOTION EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6200.001.004', '6000', 'Other Income', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6200.001.004.001', '6200.001.004', 'Other Income', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6300', '6000', 'OTHER EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6300.001.001', '6200.001-005', 'OTHER EXPENSE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6300.002.001', '6200.001-005', 'TAX EXPENSE PPH IMPOR', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6300.002.002', '6200.001-005', 'TAXEXPENSE PPN IMPOR', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('6300.002.003', '6200.001-005', 'TAX EXPENSE ', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('7000', '', 'OTHER INCOME', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('7000.001.001', '7000', 'BUNGA BANK', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('7000.001.002', '7000', 'SERVICE CHARGE', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a'),
	('7000.001.003', '7000', 'OTHER INCOME', '', 1, '1', 1, '2024-01-01 00:00:00', '', '2024-02-14 12:31:20', '1a');

-- Dumping structure for table open_akunting.account_balance
CREATE TABLE IF NOT EXISTS `account_balance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` int(4) NOT NULL DEFAULT 2024,
  `month` int(2) NOT NULL DEFAULT 1,
  `accountId` varchar(50) NOT NULL DEFAULT '',
  `outletId` varchar(50) NOT NULL DEFAULT '',
  `beginBalance` double NOT NULL DEFAULT 0,
  `debit` double NOT NULL DEFAULT 0,
  `credit` double NOT NULL DEFAULT 0,
  `endBalance` double NOT NULL DEFAULT 0,
  `status` varchar(2) NOT NULL DEFAULT '1',
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `inputBy` varchar(50) NOT NULL DEFAULT '',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateBy` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.account_balance: ~11 rows (approximately)
INSERT INTO `account_balance` (`id`, `year`, `month`, `accountId`, `outletId`, `beginBalance`, `debit`, `credit`, `endBalance`, `status`, `presence`, `inputDate`, `inputBy`, `updateDate`, `updateBy`) VALUES
	(38, 2024, 2, '1110.001.000', '1', 0, 400000, 0, 400000, '1', 1, '2024-02-15 15:46:54', '1a', '2024-02-15 16:19:01', '1a'),
	(39, 2024, 2, '3120.002.000', '2', 0, 0, 400000, 400000, '1', 1, '2024-02-15 15:46:54', '1a', '2024-02-15 16:19:01', '1a'),
	(40, 2024, 2, '1120.091.002', '1', 0, 100000, 0, 100000, '1', 1, '2024-02-15 15:47:10', '1a', '2024-02-15 15:47:10', '1a'),
	(41, 2024, 2, '7000.001.001', '2', 0, 0, 100000, 100000, '1', 1, '2024-02-15 15:47:10', '1a', '2024-02-15 15:47:10', '1a'),
	(42, 2024, 2, '1110.009.000', '1', 0, 0, 0, 0, '1', 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	(43, 2024, 2, '6120.001.005', '2', 0, 0, 0, 0, '1', 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	(44, 2024, 2, '1110.021.000', '1', 0, 10000, 0, 10000, '1', 1, '2024-02-15 15:48:08', '1a', '2024-02-15 16:19:29', '1a'),
	(45, 2024, 2, '1120.090.002', '4', 0, 0, 10000, 10000, '1', 1, '2024-02-15 15:48:08', '1a', '2024-02-15 16:19:29', '1a'),
	(46, 2024, 2, '1120.090.003', '1', 0, 0, 0, 0, '1', 1, '2024-02-15 15:48:31', '1a', '2024-02-15 15:48:31', '1a'),
	(47, 2024, 2, '1120.009.000', '4', 0, 0, 0, 0, '1', 1, '2024-02-15 15:48:31', '1a', '2024-02-15 15:48:31', '1a'),
	(48, 2024, 2, '7000.001.001', '1', 0, 0, 0, 0, '1', 1, '2024-02-15 15:48:31', '1a', '2024-02-15 15:48:31', '1a');

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

-- Dumping data for table open_akunting.account_type: ~8 rows (approximately)
INSERT INTO `account_type` (`id`, `name`, `normalBalance`, `position`, `status`, `presence`, `inputDate`, `inputBy`, `updateDate`, `updateBy`) VALUES
	('100', 'CASH AND BANK', 'D', 'BS', '1', 1, '2024-01-01 00:00:00', '', '2024-02-13 05:50:49', '1a'),
	('101', 'ACCOUNT RECEIVABLE', 'D', 'BS', '1', 1, '2024-01-01 00:00:00', '', '2024-02-13 05:50:49', '1a'),
	('102', 'INVENTORY', 'D', 'BS', '1', 1, '2024-01-01 00:00:00', '', '2024-02-13 05:50:49', '1a'),
	('103', 'FIX ASSET', 'D', 'BS', '1', 1, '2024-01-01 00:00:00', '', '2024-02-13 05:50:49', '1a'),
	('12', 'Pendapatan', 'C', 'PL', '1', 0, '2024-01-01 00:00:00', '', '2024-02-10 08:42:29', '1a'),
	('300', 'ACCUMULATE DEPRETIATION', 'D', 'BS', '1', 1, '2024-02-10 08:36:48', '1a', '2024-02-13 05:50:49', '1a'),
	('400', 'DEPOSIT', 'D', 'BS', '1', 1, '2024-02-10 08:40:44', '1a', '2024-02-13 05:50:49', '1a'),
	('500', 'Advance', 'D', 'BS', '1', 1, '2024-02-13 05:45:48', '1a', '2024-02-13 05:50:49', '1a'),
	('600', 'LONG TERM INVESMENT', 'D', 'BS', '1', 1, '2024-02-13 05:46:04', '1a', '2024-02-13 05:50:49', '1a');

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
) ENGINE=InnoDB AUTO_INCREMENT=309 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table open_akunting.auto_number: ~4 rows (approximately)
INSERT INTO `auto_number` (`id`, `name`, `prefix`, `digit`, `runningNumber`, `lastRecord`, `updateDate`) VALUES
	(305, 'account', 'A', 6, 6, 'A000006', 2024),
	(306, 'journal', 'J', 6, 84, 'J000084', 1708013969),
	(307, 'template', 'T', 6, 9, 'T000009', 1707976351),
	(308, 'cash_bank', 'CB', 6, 42, 'CB000042', 1708013941);

-- Dumping structure for table open_akunting.branch
CREATE TABLE IF NOT EXISTS `branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL DEFAULT '',
  `status` varchar(2) NOT NULL DEFAULT '1',
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `inputBy` varchar(50) NOT NULL DEFAULT '',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateBy` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.branch: ~4 rows (approximately)
INSERT INTO `branch` (`id`, `name`, `status`, `presence`, `inputDate`, `inputBy`, `updateDate`, `updateBy`) VALUES
	(1, 'Grand Indonesia', '1', 1, '2024-01-01 00:00:00', '', '2024-01-01 00:00:00', ''),
	(2, 'Kota Kasablangka', '1', 1, '2024-01-01 00:00:00', '', '2024-01-01 00:00:00', ''),
	(3, 'Taman Anggrek', '1', 1, '2024-01-01 00:00:00', '', '2024-01-01 00:00:00', ''),
	(4, 'Central Park', '1', 1, '2024-01-01 00:00:00', '', '2024-01-01 00:00:00', '');

-- Dumping structure for table open_akunting.cash_bank
CREATE TABLE IF NOT EXISTS `cash_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `journalId` varchar(50) NOT NULL DEFAULT '',
  `journalDate` date NOT NULL DEFAULT '2024-01-01',
  `outletId` varchar(250) NOT NULL DEFAULT '',
  `accountId` varchar(250) NOT NULL DEFAULT '',
  `description` varchar(250) NOT NULL DEFAULT '',
  `debit` double NOT NULL DEFAULT 0,
  `credit` double NOT NULL DEFAULT 0,
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `inputBy` varchar(50) NOT NULL DEFAULT '',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateBy` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.cash_bank: ~22 rows (approximately)
INSERT INTO `cash_bank` (`id`, `journalId`, `journalDate`, `outletId`, `accountId`, `description`, `debit`, `credit`, `presence`, `inputDate`, `inputBy`, `updateDate`, `updateBy`) VALUES
	(235, 'CB000032', '2024-02-15', '1', '1110.001.000', '11', 100000, 0, 1, '2024-02-15 15:46:54', '1a', '2024-02-15 15:46:54', '1a'),
	(236, 'CB000032', '2024-02-15', '2', '3120.002.000', '22', 0, 100000, 1, '2024-02-15 15:46:54', '1a', '2024-02-15 15:46:54', '1a'),
	(237, 'CB000033', '2024-02-15', '1', '1120.091.002', '11', 100000, 0, 1, '2024-02-15 15:47:10', '1a', '2024-02-15 15:47:10', '1a'),
	(238, 'CB000033', '2024-02-15', '2', '7000.001.001', '22', 0, 100000, 1, '2024-02-15 15:47:10', '1a', '2024-02-15 15:47:10', '1a'),
	(239, 'CB000034', '2024-03-05', '1', '1110.009.000', '11', 100000, 0, 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	(240, 'CB000034', '2024-03-05', '2', '6120.001.005', '22', 0, 100000, 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	(241, 'CB000035', '2024-04-05', '1', '1110.009.000', '11', 100000, 0, 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	(242, 'CB000035', '2024-04-05', '2', '6120.001.005', '22', 0, 100000, 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	(243, 'CB000036', '2024-05-05', '1', '1110.009.000', '11', 100000, 0, 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	(244, 'CB000036', '2024-05-05', '2', '6120.001.005', '22', 0, 100000, 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	(245, 'CB000037', '2024-06-05', '1', '1110.009.000', '11', 100000, 0, 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	(246, 'CB000037', '2024-06-05', '2', '6120.001.005', '22', 0, 100000, 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	(247, 'CB000038', '2024-07-05', '1', '1110.009.000', '11', 100000, 0, 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	(248, 'CB000038', '2024-07-05', '2', '6120.001.005', '22', 0, 100000, 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	(249, 'CB000039', '2024-08-05', '1', '1110.009.000', '11', 100000, 0, 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	(250, 'CB000039', '2024-08-05', '2', '6120.001.005', '22', 0, 100000, 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	(251, 'CB000040', '2024-02-15', '1', '1110.001.000', '11', 100000, 0, 1, '2024-02-15 16:17:46', '1a', '2024-02-15 16:17:46', '1a'),
	(252, 'CB000040', '2024-02-15', '2', '3120.002.000', '22', 0, 100000, 1, '2024-02-15 16:17:46', '1a', '2024-02-15 16:17:46', '1a'),
	(253, 'CB000041', '2024-02-15', '1', '1110.001.000', '11', 100000, 0, 1, '2024-02-15 16:18:22', '1a', '2024-02-15 16:18:22', '1a'),
	(254, 'CB000041', '2024-02-15', '2', '3120.002.000', '22', 0, 100000, 1, '2024-02-15 16:18:22', '1a', '2024-02-15 16:18:22', '1a'),
	(255, 'CB000042', '2024-02-15', '1', '1110.001.000', '11', 100000, 0, 1, '2024-02-15 16:19:01', '1a', '2024-02-15 16:19:01', '1a'),
	(256, 'CB000042', '2024-02-15', '2', '3120.002.000', '22', 0, 100000, 1, '2024-02-15 16:19:01', '1a', '2024-02-15 16:19:01', '1a');

-- Dumping structure for table open_akunting.cash_bank_header
CREATE TABLE IF NOT EXISTS `cash_bank_header` (
  `id` varchar(50) NOT NULL DEFAULT '',
  `note` varchar(250) NOT NULL DEFAULT '',
  `ref` varchar(50) NOT NULL DEFAULT '',
  `journalDate` date NOT NULL DEFAULT '2024-01-01',
  `totalDebit` double NOT NULL DEFAULT 0,
  `totalCredit` double NOT NULL DEFAULT 0,
  `templateId` varchar(50) NOT NULL DEFAULT '',
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `inputBy` varchar(50) NOT NULL DEFAULT '',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateBy` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.cash_bank_header: ~11 rows (approximately)
INSERT INTO `cash_bank_header` (`id`, `note`, `ref`, `journalDate`, `totalDebit`, `totalCredit`, `templateId`, `presence`, `inputDate`, `inputBy`, `updateDate`, `updateBy`) VALUES
	('CB000032', 'ntoe apakah masuk ?123123', 'ref 123', '2024-02-15', 100000, 100000, '', 1, '2024-02-15 15:46:54', '1a', '2024-02-15 15:46:54', '1a'),
	('CB000033', 'ntoe apakah masuk ?', 'ref 123', '2024-02-15', 100000, 100000, '', 1, '2024-02-15 15:47:10', '1a', '2024-02-15 15:47:10', '1a'),
	('CB000034', 'ntoe apakah masuk ?123123', 'ref 123', '2024-03-05', 100000, 100000, '', 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	('CB000035', 'ntoe apakah masuk ?123123', 'ref 123', '2024-04-05', 200000, 200000, '', 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	('CB000036', 'ntoe apakah masuk ?123123', 'ref 123', '2024-05-05', 300000, 300000, '', 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	('CB000037', 'ntoe apakah masuk ?123123', 'ref 123', '2024-06-05', 400000, 400000, '', 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	('CB000038', 'ntoe apakah masuk ?123123', 'ref 123', '2024-07-05', 500000, 500000, '', 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	('CB000039', 'ntoe apakah masuk ?123123', 'ref 123', '2024-08-05', 600000, 600000, '', 1, '2024-02-15 15:47:57', '1a', '2024-02-15 15:47:57', '1a'),
	('CB000040', 'ntoe apakah masuk ?123123', 'ref 123', '2024-02-15', 100000, 100000, 'T000008', 1, '2024-02-15 16:17:46', '1a', '2024-02-15 16:17:46', '1a'),
	('CB000041', 'ntoe apakah masuk ?123123', 'ref 123', '2024-02-15', 100000, 100000, 'T000008', 1, '2024-02-15 16:18:22', '1a', '2024-02-15 16:18:22', '1a'),
	('CB000042', 'ntoe apakah masuk ?123123', 'ref 123', '2024-02-15', 100000, 100000, 'T000008', 1, '2024-02-15 16:19:01', '1a', '2024-02-15 16:19:01', '1a');

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `journalId` varchar(50) NOT NULL DEFAULT '',
  `journalDate` date NOT NULL DEFAULT '2024-01-01',
  `outletId` varchar(250) NOT NULL DEFAULT '',
  `accountId` varchar(250) NOT NULL DEFAULT '',
  `description` varchar(250) NOT NULL DEFAULT '',
  `debit` double NOT NULL DEFAULT 0,
  `credit` double NOT NULL DEFAULT 0,
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `inputBy` varchar(50) NOT NULL DEFAULT '',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateBy` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.journal: ~2 rows (approximately)
INSERT INTO `journal` (`id`, `journalId`, `journalDate`, `outletId`, `accountId`, `description`, `debit`, `credit`, `presence`, `inputDate`, `inputBy`, `updateDate`, `updateBy`) VALUES
	(192, 'J000084', '2024-02-15', '1', '1110.021.000', '', 10000, 0, 1, '2024-02-15 16:19:29', '1a', '2024-02-15 16:19:29', '1a'),
	(193, 'J000084', '2024-02-15', '4', '1120.090.002', '', 0, 10000, 1, '2024-02-15 16:19:29', '1a', '2024-02-15 16:19:29', '1a');

-- Dumping structure for table open_akunting.journal_header
CREATE TABLE IF NOT EXISTS `journal_header` (
  `id` varchar(50) NOT NULL DEFAULT '',
  `note` varchar(250) NOT NULL DEFAULT '',
  `ref` varchar(50) NOT NULL DEFAULT '',
  `journalDate` date NOT NULL DEFAULT '2024-01-01',
  `totalDebit` double NOT NULL DEFAULT 0,
  `totalCredit` double NOT NULL DEFAULT 0,
  `templateId` varchar(50) NOT NULL DEFAULT '',
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `inputBy` varchar(50) NOT NULL DEFAULT '',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateBy` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.journal_header: ~1 rows (approximately)
INSERT INTO `journal_header` (`id`, `note`, `ref`, `journalDate`, `totalDebit`, `totalCredit`, `templateId`, `presence`, `inputDate`, `inputBy`, `updateDate`, `updateBy`) VALUES
	('J000084', '123 Note', 'ref', '2024-02-15', 10000, 10000, 'T000002', 1, '2024-02-15 16:19:29', '1a', '2024-02-15 16:19:29', '1a');

-- Dumping structure for table open_akunting.journal_template
CREATE TABLE IF NOT EXISTS `journal_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `templateId` varchar(50) NOT NULL DEFAULT '',
  `JournalDate` date NOT NULL DEFAULT '2024-01-01',
  `outletId` varchar(250) NOT NULL DEFAULT '',
  `accountId` varchar(250) NOT NULL DEFAULT '',
  `description` varchar(250) NOT NULL DEFAULT '',
  `debit` double NOT NULL DEFAULT 0,
  `credit` double NOT NULL DEFAULT 0,
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `inputBy` varchar(50) NOT NULL DEFAULT '',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateBy` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=162 DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.journal_template: ~56 rows (approximately)
INSERT INTO `journal_template` (`id`, `templateId`, `JournalDate`, `outletId`, `accountId`, `description`, `debit`, `credit`, `presence`, `inputDate`, `inputBy`, `updateDate`, `updateBy`) VALUES
	(106, 'T000002', '2024-01-01', '1', '1110.021.000', '', 1, 0, 0, '2024-02-13 10:03:55', '1a', '2024-02-13 17:10:44', '1a'),
	(107, 'T000002', '2024-01-01', '4', '1120.090.002', '', 0, 1, 0, '2024-02-13 10:03:55', '1a', '2024-02-13 17:10:44', '1a'),
	(108, 'T000003', '2024-01-01', '1', '1110.021.000', '', 6000000, 0, 1, '2024-02-13 10:25:41', '1a', '2024-02-13 10:25:41', '1a'),
	(109, 'T000003', '2024-01-01', '4', '1120.090.002', '', 0, 5000000, 1, '2024-02-13 10:25:41', '1a', '2024-02-13 10:25:41', '1a'),
	(110, 'T000003', '2024-01-01', '1', '7000.001.001', '', 0, 1000000, 1, '2024-02-13 10:25:41', '1a', '2024-02-13 10:25:41', '1a'),
	(111, 'T000004', '2024-01-01', '1', '1110.021.000', '', 6000000, 0, 1, '2024-02-13 10:25:45', '1a', '2024-02-13 10:25:45', '1a'),
	(112, 'T000004', '2024-01-01', '4', '1120.090.002', '', 0, 5000000, 1, '2024-02-13 10:25:45', '1a', '2024-02-13 10:25:45', '1a'),
	(113, 'T000004', '2024-01-01', '1', '7000.001.001', '', 0, 1000000, 1, '2024-02-13 10:25:45', '1a', '2024-02-13 10:25:45', '1a'),
	(114, 'T000005', '2024-01-01', '1', '1110.021.000', '1', 6000000, 0, 0, '2024-02-13 10:33:34', '1a', '2024-02-13 10:43:18', '1a'),
	(115, 'T000005', '2024-01-01', '4', '1120.090.002', '23', 0, 5000000, 0, '2024-02-13 10:33:34', '1a', '2024-02-13 10:43:18', '1a'),
	(116, 'T000005', '2024-01-01', '1', '7000.001.001', '3', 0, 1000000, 0, '2024-02-13 10:33:34', '1a', '2024-02-13 10:43:18', '1a'),
	(117, 'T000005', '2024-01-01', '1', '1110.021.000', '1', 6000000, 0, 0, '2024-02-13 10:33:46', '1a', '2024-02-13 10:43:18', '1a'),
	(118, 'T000005', '2024-01-01', '4', '1120.090.002', '23', 0, 5000000, 0, '2024-02-13 10:33:46', '1a', '2024-02-13 10:43:18', '1a'),
	(119, 'T000005', '2024-01-01', '1', '7000.001.001', '3', 0, 1000000, 0, '2024-02-13 10:33:46', '1a', '2024-02-13 10:43:18', '1a'),
	(120, 'T000005', '2024-01-01', '1', '1110.021.000', '1', 6000000, 0, 0, '2024-02-13 10:34:04', '1a', '2024-02-13 10:43:18', '1a'),
	(121, 'T000005', '2024-01-01', '4', '1120.090.002', '23', 0, 5000000, 0, '2024-02-13 10:34:04', '1a', '2024-02-13 10:43:18', '1a'),
	(122, 'T000005', '2024-01-01', '1', '7000.001.001', '3', 0, 1000000, 0, '2024-02-13 10:34:04', '1a', '2024-02-13 10:43:18', '1a'),
	(123, 'T000005', '2024-01-01', '1', '1110.021.000', '1', 6000000, 0, 0, '2024-02-13 10:34:10', '1a', '2024-02-13 10:43:18', '1a'),
	(124, 'T000005', '2024-01-01', '4', '1120.090.002', '23', 0, 5000000, 0, '2024-02-13 10:34:10', '1a', '2024-02-13 10:43:18', '1a'),
	(125, 'T000005', '2024-01-01', '1', '1110.021.000', '', 6000000, 0, 0, '2024-02-13 10:34:30', '1a', '2024-02-13 10:43:18', '1a'),
	(126, 'T000005', '2024-01-01', '4', '1120.090.002', '', 0, 5000000, 0, '2024-02-13 10:34:30', '1a', '2024-02-13 10:43:18', '1a'),
	(127, 'T000005', '2024-01-01', '1', '7000.001.001', '', 0, 1000000, 0, '2024-02-13 10:34:30', '1a', '2024-02-13 10:43:18', '1a'),
	(128, 'T000005', '2024-01-01', '1', '1110.021.000', '', 6000000, 0, 0, '2024-02-13 10:34:41', '1a', '2024-02-13 10:43:18', '1a'),
	(129, 'T000005', '2024-01-01', '4', '1120.090.002', '', 0, 5000000, 0, '2024-02-13 10:34:41', '1a', '2024-02-13 10:43:18', '1a'),
	(130, 'T000005', '2024-01-01', '1', '7000.001.001', '', 0, 1000000, 0, '2024-02-13 10:34:41', '1a', '2024-02-13 10:43:18', '1a'),
	(131, 'T000005', '2024-01-01', '1', '1110.021.000', '1', 6000000, 0, 0, '2024-02-13 10:40:22', '1a', '2024-02-13 10:43:18', '1a'),
	(132, 'T000005', '2024-01-01', '4', '1120.090.002', '23', 0, 5000000, 0, '2024-02-13 10:40:22', '1a', '2024-02-13 10:43:18', '1a'),
	(133, 'T000005', '2024-01-01', '4', '1120.090.002', '23', 500000, 0, 0, '2024-02-13 10:40:50', '1a', '2024-02-13 10:43:18', '1a'),
	(134, 'T000005', '2024-01-01', '', '1120.091.001', '', 0, 400000, 0, '2024-02-13 10:40:50', '1a', '2024-02-13 10:43:18', '1a'),
	(135, 'T000005', '2024-01-01', '4', '1120.090.002', '23', 500000, 0, 0, '2024-02-13 10:41:07', '1a', '2024-02-13 10:43:18', '1a'),
	(136, 'T000005', '2024-01-01', '', '1120.091.001', '', 0, 400000, 0, '2024-02-13 10:41:07', '1a', '2024-02-13 10:43:18', '1a'),
	(137, 'T000005', '2024-01-01', '1', '1120.091.001', '1', 50000, 0, 0, '2024-02-13 10:42:03', '1a', '2024-02-13 10:43:18', '1a'),
	(138, 'T000005', '2024-01-01', '1', '7000.001.003', '2', 0, 50000, 0, '2024-02-13 10:42:03', '1a', '2024-02-13 10:43:18', '1a'),
	(139, 'T000005', '2024-01-01', '1', '7000.001.003', '2', 0, 50000, 0, '2024-02-13 10:42:56', '1a', '2024-02-13 10:43:18', '1a'),
	(140, 'T000005', '2024-01-01', '1', '1110.021.000', '3', 50000, 0, 0, '2024-02-13 10:42:56', '1a', '2024-02-13 10:43:18', '1a'),
	(141, 'T000005', '2024-01-01', '1', '7000.001.003', '2', 0, 100000, 1, '2024-02-13 10:43:18', '1a', '2024-02-13 10:43:18', '1a'),
	(142, 'T000005', '2024-01-01', '1', '1110.021.000', '3', 50000, 0, 1, '2024-02-13 10:43:18', '1a', '2024-02-13 10:43:18', '1a'),
	(143, 'T000005', '2024-01-01', '1', '1120.090.002', '4', 50000, 0, 1, '2024-02-13 10:43:18', '1a', '2024-02-13 10:43:18', '1a'),
	(144, 'T000002', '2024-01-01', '1', '1110.021.000', '', 10000, 0, 1, '2024-02-13 17:10:44', '1a', '2024-02-13 17:10:44', '1a'),
	(145, 'T000002', '2024-01-01', '4', '1120.090.002', '', 0, 10000, 1, '2024-02-13 17:10:44', '1a', '2024-02-13 17:10:44', '1a'),
	(146, 'T000006', '2024-01-01', '1', '1110.001.000', '1', 100000, 0, 0, '2024-02-14 11:51:33', '1a', '2024-02-14 11:52:04', '1a'),
	(147, 'T000006', '2024-01-01', '2', '3120.002.000', '2', 0, 100000, 0, '2024-02-14 11:51:33', '1a', '2024-02-14 11:52:04', '1a'),
	(148, 'T000006', '2024-01-01', '1', '1110.001.000', '11', 100000, 0, 1, '2024-02-14 11:52:04', '1a', '2024-02-14 11:52:04', '1a'),
	(149, 'T000006', '2024-01-01', '2', '3120.002.000', '22', 0, 100000, 1, '2024-02-14 11:52:04', '1a', '2024-02-14 11:52:04', '1a'),
	(150, 'T000007', '2024-01-01', '1', '1110.001.000', '11', 100000, 0, 1, '2024-02-14 11:52:14', '1a', '2024-02-14 11:52:14', '1a'),
	(151, 'T000007', '2024-01-01', '2', '3120.002.000', '22', 0, 100000, 1, '2024-02-14 11:52:14', '1a', '2024-02-14 11:52:14', '1a'),
	(152, 'T000008', '2024-01-01', '1', '1110.001.000', '11', 100000, 0, 0, '2024-02-14 12:11:51', '1a', '2024-02-14 12:15:50', '1a'),
	(153, 'T000008', '2024-01-01', '2', '3120.002.000', '22', 0, 100000, 0, '2024-02-14 12:11:51', '1a', '2024-02-14 12:15:50', '1a'),
	(154, 'T000008', '2024-01-01', '1', '1110.001.000', '11', 100000, 0, 0, '2024-02-14 12:12:10', '1a', '2024-02-14 12:15:50', '1a'),
	(155, 'T000008', '2024-01-01', '2', '3120.002.000', '22', 0, 100000, 0, '2024-02-14 12:12:10', '1a', '2024-02-14 12:15:50', '1a'),
	(156, 'T000008', '2024-01-01', '1', '1110.001.000', '11', 100000, 0, 1, '2024-02-14 12:15:50', '1a', '2024-02-14 12:15:50', '1a'),
	(157, 'T000008', '2024-01-01', '2', '3120.002.000', '22', 0, 100000, 1, '2024-02-14 12:15:50', '1a', '2024-02-14 12:15:50', '1a'),
	(158, 'T000009', '2024-01-01', '1', '1110.021.000', '', 10000, 0, 0, '2024-02-15 05:52:31', '1a', '2024-02-15 05:52:39', '1a'),
	(159, 'T000009', '2024-01-01', '4', '1120.090.002', '', 0, 10000, 0, '2024-02-15 05:52:31', '1a', '2024-02-15 05:52:39', '1a'),
	(160, 'T000009', '2024-01-01', '1', '1110.021.000', '1', 10000, 0, 1, '2024-02-15 05:52:39', '1a', '2024-02-15 05:52:39', '1a'),
	(161, 'T000009', '2024-01-01', '4', '1120.090.002', '2', 0, 10000, 1, '2024-02-15 05:52:39', '1a', '2024-02-15 05:52:39', '1a');

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

-- Dumping structure for table open_akunting.outlet
CREATE TABLE IF NOT EXISTS `outlet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branchId` int(11) NOT NULL DEFAULT 0,
  `name` varchar(250) NOT NULL DEFAULT '',
  `status` varchar(2) NOT NULL DEFAULT '1',
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `inputBy` varchar(50) NOT NULL DEFAULT '',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateBy` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.outlet: ~6 rows (approximately)
INSERT INTO `outlet` (`id`, `branchId`, `name`, `status`, `presence`, `inputDate`, `inputBy`, `updateDate`, `updateBy`) VALUES
	(1, 1, 'Lt 1 F', '1', 1, '2024-01-01 00:00:00', '', '2024-01-01 00:00:00', ''),
	(2, 1, 'Lt 5 F', '1', 1, '2024-01-01 00:00:00', '', '2024-01-01 00:00:00', ''),
	(3, 2, 'Lt GF F', '1', 1, '2024-01-01 00:00:00', '', '2024-01-01 00:00:00', ''),
	(4, 2, 'Lt UF', '1', 1, '2024-01-01 00:00:00', '', '2024-01-01 00:00:00', ''),
	(5, 3, 'Ruko 1', '1', 1, '2024-01-01 00:00:00', '', '2024-01-01 00:00:00', ''),
	(6, 3, 'Ruko 2', '1', 1, '2024-01-01 00:00:00', '', '2024-01-01 00:00:00', '');

-- Dumping structure for table open_akunting.template
CREATE TABLE IF NOT EXISTS `template` (
  `id` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `tableName` varchar(250) NOT NULL DEFAULT '',
  `note` varchar(250) NOT NULL DEFAULT '',
  `ref` varchar(250) NOT NULL DEFAULT '',
  `presence` int(1) NOT NULL DEFAULT 1,
  `inputDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `inputBy` varchar(50) NOT NULL DEFAULT '',
  `updateDate` datetime NOT NULL DEFAULT '2024-01-01 00:00:00',
  `updateBy` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

-- Dumping data for table open_akunting.template: ~7 rows (approximately)
INSERT INTO `template` (`id`, `name`, `tableName`, `note`, `ref`, `presence`, `inputDate`, `inputBy`, `updateDate`, `updateBy`) VALUES
	('T000002', 'Trans Bank', 'journal_template', '123 Note', 'ref', 1, '2024-02-13 10:03:55', '1a', '2024-02-13 17:10:44', '1a'),
	('T000003', 'template save 2', 'journal_template', 'tagihan bulanan', 'Ref 0001', 1, '2024-02-13 10:25:41', '1a', '2024-02-13 10:25:41', '1a'),
	('T000004', 'template save 3', 'journal_template', 'tagihan bulanan', 'Ref 0001', 1, '2024-02-13 10:25:45', '1a', '2024-02-13 10:25:45', '1a'),
	('T000005', 'save 4', 'journal_template', 'tagihan bulanan update 5', 'Ref 0001', 1, '2024-02-13 10:33:34', '1a', '2024-02-13 10:43:18', '1a'),
	('T000006', 'CB1', 'cash_bank', 'ntoe apakah masuk ?', 'ref 123', 1, '2024-02-14 11:51:33', '1a', '2024-02-14 11:52:04', '1a'),
	('T000007', 'CB2', 'cash_bank', 'ntoe apakah masuk ?', 'ref 123', 1, '2024-02-14 11:52:14', '1a', '2024-02-14 11:52:14', '1a'),
	('T000008', 'CB 1', 'cash_bank', 'ntoe apakah masuk ?123123', 'ref 123', 1, '2024-02-14 12:11:51', '1a', '2024-02-14 12:15:50', '1a'),
	('T000009', 'Trans Bank 1', 'journal_template', '123 Note', 'ref', 1, '2024-02-15 05:52:31', '1a', '2024-02-15 05:52:39', '1a');

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
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table open_akunting.user_jti: ~48 rows (approximately)
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
	(106, '1a', 'c8fbd2c5883aadf333265d79a81e7173', '2024-02-11 17:23:34'),
	(107, '1a', 'fbbcd9669dbf5717f2ea92fb8c5bb3cd', '2024-02-12 04:55:46'),
	(108, '1a', 'f560ea5bdf8079a62087c1c4ddb1b57e', '2024-02-12 04:55:46');

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
