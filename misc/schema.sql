/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE TABLE IF NOT EXISTS `tbaccount` (
  `accountID` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountName` varchar(255) DEFAULT NULL,
  `accountEmail` varchar(255) DEFAULT NULL,
  `accountPasswd` text,
  `accountPasswdExpired` text,
  `accountLanguage` varchar(10) DEFAULT 'EN',
  `accountLocale` varchar(10) DEFAULT 'en-US',
  PRIMARY KEY (`accountID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `tbcategory` (
  `categoryID` bigint(20) NOT NULL AUTO_INCREMENT,
  `categoryAccountID` bigint(20) DEFAULT '0',
  `categoryName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`categoryID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `tbexpense` (
  `expenseID` bigint(20) NOT NULL AUTO_INCREMENT,
  `expenseAccountID` bigint(20) NOT NULL DEFAULT '0',
  `expensePurchaseID` bigint(20) NOT NULL DEFAULT '0',
  `expenseLineID` varchar(50) DEFAULT NULL,
  `expenseProductID` bigint(20) NOT NULL DEFAULT '0',
  `expenseProductName` varchar(255) DEFAULT NULL,
  `expenseQuantity` int(11) NOT NULL DEFAULT '0',
  `expenseProductSize` int(11) NOT NULL DEFAULT '0',
  `expenseProductFormat` varchar(50) NOT NULL DEFAULT '0',
  `expensePrice` double NOT NULL DEFAULT '0',
  `expenseEquName` varchar(255) NOT NULL DEFAULT '0',
  `expenseEquProductSize` int(11) NOT NULL DEFAULT '0',
  `expenseEquPrice` double NOT NULL DEFAULT '0',
  `expenseExtra` double NOT NULL DEFAULT '0',
  `expenseNote` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`expenseID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `tbperson` (
  `personID` bigint(20) NOT NULL AUTO_INCREMENT,
  `personAccountID` bigint(20) NOT NULL DEFAULT '0',
  `personName` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`personID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `tbproduct` (
  `productID` bigint(20) NOT NULL AUTO_INCREMENT,
  `productAccountID` bigint(20) NOT NULL DEFAULT '0',
  `productSKU` varchar(50) NOT NULL DEFAULT '0',
  `productName` varchar(255) NOT NULL DEFAULT '0',
  `productCategoryID` bigint(20) NOT NULL DEFAULT '0',
  `productPrice` double NOT NULL DEFAULT '0',
  `productSize` int(11) NOT NULL DEFAULT '0',
  `productFormat` varchar(50) NOT NULL DEFAULT '0',
  `productEquName` varchar(255) NOT NULL DEFAULT '0',
  `productEquSize` int(11) NOT NULL DEFAULT '0',
  `productEquPrice` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`productID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `tbpurchase` (
  `purchaseID` bigint(20) NOT NULL AUTO_INCREMENT,
  `purchaseAccountID` bigint(20) NOT NULL DEFAULT '0',
  `purchaseDate` date DEFAULT NULL,
  `purchaseReference` varchar(255) NOT NULL DEFAULT '0',
  `purchasePersonID` bigint(20) NOT NULL DEFAULT '0',
  `purchaseStoreID` bigint(20) NOT NULL DEFAULT '0',
  `purchaseAmountNormal` double NOT NULL DEFAULT '0',
  `purchaseAmountGF` double NOT NULL DEFAULT '0',
  `purchaseAmountExtra` double NOT NULL DEFAULT '0',
  `purchaseNote` text,
  PRIMARY KEY (`purchaseID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `tbstore` (
  `storeID` bigint(20) NOT NULL AUTO_INCREMENT,
  `storeAccountID` bigint(20) DEFAULT '0',
  `storeName` varchar(255) DEFAULT NULL,
  `storeAddress` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`storeID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
