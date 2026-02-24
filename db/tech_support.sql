-- SportsPro Tech Support
-- Updated to create/use database tech_support1 + added more sample data

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `tech_support1`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE `tech_support1`;

-- Drop tables in FK-safe order 
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `registrations`;
DROP TABLE IF EXISTS `incidents`;
DROP TABLE IF EXISTS `technicians`;
DROP TABLE IF EXISTS `customers`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `countries`;
DROP TABLE IF EXISTS `administrators`;
SET FOREIGN_KEY_CHECKS=1;

-- --------------------------------------------------------
-- administrators
-- --------------------------------------------------------
CREATE TABLE `administrators` (
  `adminID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  PRIMARY KEY (`adminID`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `administrators` (`adminID`, `username`, `passwordHash`) VALUES
(1, 'admin', '$2y$10$Lck0fdd2u7JL4DfBHzTHhOwytWA8fRxwPbP2CMlJyJaODUZ.cdpHC');

-- --------------------------------------------------------
-- countries
-- --------------------------------------------------------
CREATE TABLE `countries` (
  `countryCode` char(2) NOT NULL,
  `countryName` varchar(100) NOT NULL,
  PRIMARY KEY (`countryCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `countries` (`countryCode`, `countryName`) VALUES
('CA', 'Canada'),
('US', 'United States');

-- --------------------------------------------------------
-- products
-- --------------------------------------------------------
CREATE TABLE `products` (
  `productCode` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `version` varchar(20) DEFAULT NULL,
  `releaseDate` date DEFAULT NULL,
  PRIMARY KEY (`productCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `products` (`productCode`, `name`, `version`, `releaseDate`) VALUES
('BB10', 'Baseball Pro', '1.0', '2025-09-01'),
('SC15', 'Soccer Pro', '1.5', '2025-06-15');

-- --------------------------------------------------------
-- technicians
-- --------------------------------------------------------
CREATE TABLE `technicians` (
  `techID` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `passwordHash` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`techID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `technicians` (`techID`, `firstName`, `lastName`, `email`, `phone`, `passwordHash`) VALUES
(1, 'Taylor', 'Ng', 'tng@sportspro.com', '416-555-9876', '$2y$10$Lck0fdd2u7JL4DfBHzTHhOwytWA8fRxwPbP2CMlJyJaODUZ.cdpHC'),
(2, 'Chris', 'Patel', 'cpatel@sportspro.com', '416-555-4567', '$2y$10$Lck0fdd2u7JL4DfBHzTHhOwytWA8fRxwPbP2CMlJyJaODUZ.cdpHC'),
(3, 'Ava', 'Reed', 'areed@sportspro.com', '519-555-1111', '$2y$10$Lck0fdd2u7JL4DfBHzTHhOwytWA8fRxwPbP2CMlJyJaODUZ.cdpHC'),
(4, 'Noah', 'Singh', 'nsingh@sportspro.com', '647-555-2222', '$2y$10$Lck0fdd2u7JL4DfBHzTHhOwytWA8fRxwPbP2CMlJyJaODUZ.cdpHC');

-- --------------------------------------------------------
-- customers
-- --------------------------------------------------------
CREATE TABLE `customers` (
  `customerID` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `postalCode` varchar(20) DEFAULT NULL,
  `countryCode` char(2) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `passwordHash` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`customerID`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_customers_country` (`countryCode`),
  CONSTRAINT `fk_customers_country`
    FOREIGN KEY (`countryCode`) REFERENCES `countries` (`countryCode`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `customers`
(`customerID`,`firstName`,`lastName`,`address`,`city`,`state`,`postalCode`,`countryCode`,`phone`,`email`,`passwordHash`) VALUES
(1, 'Alex', 'Morgan', '10 King St', 'Toronto', 'ON', 'M5H 1A1', 'CA', '416-111-2222', 'alex@example.com', '$2y$10$Lck0fdd2u7JL4DfBHzTHhOwytWA8fRxwPbP2CMlJyJaODUZ.cdpHC'),
(2, 'Jamie', 'Lee', '200 Main Ave', 'Buffalo', 'NY', '14201', 'US', '716-333-4444', 'jamie@example.com', '$2y$10$Lck0fdd2u7JL4DfBHzTHhOwytWA8fRxwPbP2CMlJyJaODUZ.cdpHC'),
(3, 'Sarah', 'Johnson', '55 Queen St', 'Toronto', 'ON', 'M5V 2B6', 'CA', '416-222-3344', 'sarah.johnson@email.com', '$2y$10$Lck0fdd2u7JL4DfBHzTHhOwytWA8fRxwPbP2CMlJyJaODUZ.cdpHC'),
(4, 'Michael', 'Brown', '789 Elm St', 'Chicago', 'IL', '60611', 'US', '312-555-7788', 'michael.brown@email.com', '$2y$10$Lck0fdd2u7JL4DfBHzTHhOwytWA8fRxwPbP2CMlJyJaODUZ.cdpHC'),
(5, 'Emily', 'Davis', '22 Sunset Blvd', 'Los Angeles', 'CA', '90028', 'US', '213-444-8899', 'emily.davis@email.com', '$2y$10$Lck0fdd2u7JL4DfBHzTHhOwytWA8fRxwPbP2CMlJyJaODUZ.cdpHC'),
(6, 'Daniel', 'Wilson', '101 King Rd', 'Vancouver', 'BC', 'V6B 1A1', 'CA', '604-333-9988', 'daniel.wilson@email.com', '$2y$10$Lck0fdd2u7JL4DfBHzTHhOwytWA8fRxwPbP2CMlJyJaODUZ.cdpHC'),
(7, 'Olivia', 'Martinez', '88 Ocean Ave', 'San Diego', 'CA', '92101', 'US', '619-777-1212', 'olivia.martinez@email.com', '$2y$10$Lck0fdd2u7JL4DfBHzTHhOwytWA8fRxwPbP2CMlJyJaODUZ.cdpHC');

-- --------------------------------------------------------
-- incidents
-- --------------------------------------------------------
CREATE TABLE `incidents` (
  `incidentID` int(11) NOT NULL AUTO_INCREMENT,
  `customerID` int(11) NOT NULL,
  `productCode` varchar(10) NOT NULL,
  `techID` int(11) DEFAULT NULL,
  `dateOpened` datetime NOT NULL DEFAULT current_timestamp(),
  `dateClosed` datetime DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`incidentID`),
  KEY `fk_incident_customer` (`customerID`),
  KEY `fk_incident_product` (`productCode`),
  KEY `fk_incident_tech` (`techID`),
  CONSTRAINT `fk_incident_customer`
    FOREIGN KEY (`customerID`) REFERENCES `customers` (`customerID`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_incident_product`
    FOREIGN KEY (`productCode`) REFERENCES `products` (`productCode`)
    ON UPDATE CASCADE,
  CONSTRAINT `fk_incident_tech`
    FOREIGN KEY (`techID`) REFERENCES `technicians` (`techID`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `incidents`
(`incidentID`,`customerID`,`productCode`,`techID`,`dateOpened`,`dateClosed`,`title`,`description`) VALUES
(1, 1, 'BB10', 1, '2026-01-28 19:52:06', NULL, 'Cannot save league', 'Error appears when saving.'),
(2, 2, 'SC15', NULL, '2026-01-28 19:52:06', NULL, 'Install issue', 'Setup fails at step 2.'),
(3, 3, 'BB10', 3, '2026-02-05 10:15:00', NULL, 'Crash on launch', 'App crashes on startup.'),
(4, 4, 'SC15', 2, '2026-02-06 14:30:00', NULL, 'Update stuck', 'Update never completes.');

-- --------------------------------------------------------
-- registrations
-- --------------------------------------------------------
CREATE TABLE `registrations` (
  `registrationID` int(11) NOT NULL AUTO_INCREMENT,
  `customerID` int(11) NOT NULL,
  `productCode` varchar(10) NOT NULL,
  `registrationDate` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`registrationID`),
  UNIQUE KEY `uq_customer_product` (`customerID`,`productCode`),
  KEY `fk_reg_product` (`productCode`),
  CONSTRAINT `fk_reg_customer`
    FOREIGN KEY (`customerID`) REFERENCES `customers` (`customerID`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_reg_product`
    FOREIGN KEY (`productCode`) REFERENCES `products` (`productCode`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `registrations`
(`registrationID`,`customerID`,`productCode`,`registrationDate`) VALUES
(1, 1, 'BB10', '2026-01-28 19:52:06'),
(2, 2, 'SC15', '2026-01-28 19:52:06'),
(3, 3, 'BB10', '2026-02-05 10:00:00'),
(4, 4, 'SC15', '2026-02-06 14:00:00');

COMMIT;
