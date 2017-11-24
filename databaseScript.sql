-- MySQL Script generated by MySQL Workbench
-- Wed Nov 22 05:36:33 2017
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema conushop
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `conushop` ;

-- -----------------------------------------------------
-- Schema conushop
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `conushop` DEFAULT CHARACTER SET utf8 ;
USE `conushop` ;

-- -----------------------------------------------------
-- Table `conushop`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `conushop`.`User` ;

CREATE TABLE IF NOT EXISTS `conushop`.`User` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `firstName` VARCHAR(45) NULL,
  `lastName` VARCHAR(45) NULL,
  `email` VARCHAR(255) NULL,
  `phone` VARCHAR(25) NULL,
  `admin` TINYINT NULL,
  `physicalAddress` VARCHAR(255) NULL,
  `password` VARCHAR(255) NULL,
  `remember_token` VARCHAR(100) NULL,
  `isDeleted` TINYINT NULL,
  `isLoggedIn` TINYINT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `conushop`.`ElectronicType`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `conushop`.`ElectronicType` ;

CREATE TABLE IF NOT EXISTS `conushop`.`ElectronicType` (
  `id` INT NOT NULL,
  `name` VARCHAR(45) NULL,
  `dimensionUnit` VARCHAR(45) NULL,
  `screenSizeUnit` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `conushop`.`ElectronicSpecification`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `conushop`.`ElectronicSpecification` ;

CREATE TABLE IF NOT EXISTS `conushop`.`ElectronicSpecification` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `dimension` VARCHAR(45) NULL,
  `weight` FLOAT NULL,
  `modelNumber` VARCHAR(45) NULL,
  `brandName` VARCHAR(45) NULL,
  `hdSize` VARCHAR(25) NULL,
  `price` VARCHAR(45) NULL,
  `processorType` VARCHAR(45) NULL,
  `ramSize` VARCHAR(45) NULL,
  `cpuCores` INT NULL,
  `batteryInfo` VARCHAR(45) NULL,
  `os` VARCHAR(45) NULL,
  `camera` TINYINT NULL,
  `touchScreen` TINYINT NULL,
  `ElectronicType_id` INT NULL,
  `displaySize` DOUBLE(5,1) NULL,
  `image` VARCHAR(45) NULL,
  `isDeleted` TINYINT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Electronic_ElectronicType_idx` (`ElectronicType_id` ASC),
  CONSTRAINT `fk_Electronic_ElectronicType`
    FOREIGN KEY (`ElectronicType_id`)
    REFERENCES `conushop`.`ElectronicType` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `conushop`.`Payment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `conushop`.`Payment` ;

CREATE TABLE IF NOT EXISTS `conushop`.`Payment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `amount` INT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `conushop`.`Sale`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `conushop`.`Sale` ;

CREATE TABLE IF NOT EXISTS `conushop`.`Sale` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `User_id` INT NOT NULL,
  `isComplete` TINYINT NULL,
  `timestamp` DATETIME NULL,
  `Payment_id` INT NULL,
  INDEX `fk_Sale_User1_idx` (`User_id` ASC),
  PRIMARY KEY (`id`),
  INDEX `fk_Sale_Payment1_idx` (`Payment_id` ASC),
  CONSTRAINT `fk_Sale_User1`
    FOREIGN KEY (`User_id`)
    REFERENCES `conushop`.`User` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Sale_Payment1`
    FOREIGN KEY (`Payment_id`)
    REFERENCES `conushop`.`Payment` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `conushop`.`ElectronicItem`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `conushop`.`ElectronicItem` ;

CREATE TABLE IF NOT EXISTS `conushop`.`ElectronicItem` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ElectronicSpecification_id` INT NULL,
  `serialNumber` VARCHAR(45) NULL,
  `User_id` INT NULL,
  `expiryForUser` DATETIME NULL,
  `Sale_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Item_Electronic1_idx` (`ElectronicSpecification_id` ASC),
  INDEX `fk_ElectronicItem_User1_idx` (`User_id` ASC),
  INDEX `fk_ElectronicItem_Sale1_idx` (`Sale_id` ASC),
  CONSTRAINT `fk_Item_Electronic1`
    FOREIGN KEY (`ElectronicSpecification_id`)
    REFERENCES `conushop`.`ElectronicSpecification` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ElectronicItem_User1`
    FOREIGN KEY (`User_id`)
    REFERENCES `conushop`.`User` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ElectronicItem_Sale1`
    FOREIGN KEY (`Sale_id`)
    REFERENCES `conushop`.`Sale` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `conushop`.`LoginLog`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `conushop`.`LoginLog` ;

CREATE TABLE IF NOT EXISTS `conushop`.`LoginLog` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `timestamp` DATETIME NULL,
  `User_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_LoginLog_User1_idx` (`User_id` ASC),
  CONSTRAINT `fk_LoginLog_User1`
    FOREIGN KEY (`User_id`)
    REFERENCES `conushop`.`User` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `conushop`.`ReturnTransaction`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `conushop`.`ReturnTransaction` ;

CREATE TABLE IF NOT EXISTS `conushop`.`ReturnTransaction` (
  `id` INT NULL AUTO_INCREMENT,
  `User_id` INT NULL,
  `ElectronicItem_id` INT NULL,
  `isComplete` TINYINT NULL,
  `timestamp` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_ReturnTransaction_User1_idx` (`User_id` ASC),
  INDEX `fk_ReturnTransaction_ElectronicItem1_idx` (`ElectronicItem_id` ASC),
  CONSTRAINT `fk_ReturnTransaction_User1`
    FOREIGN KEY (`User_id`)
    REFERENCES `conushop`.`User` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ReturnTransaction_ElectronicItem1`
    FOREIGN KEY (`ElectronicItem_id`)
    REFERENCES `conushop`.`ElectronicItem` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- INSERT
-- -----------------------------------------------------
REPLACE INTO ElectronicType (id, name) values (1, "Desktop");
REPLACE INTO ElectronicType (id, name) values (2, "Laptop");
REPLACE INTO ElectronicType (id, name) values (3, "Monitor");
REPLACE INTO ElectronicType (id, name) values (4, "Tablet");
/**REPLACE INTO ElectronicType (id, name) values (5, "Television");**/

REPLACE INTO User (id, email, password, admin, isDeleted, isLoggedIn) values (1, 'admin1@conushop.com', '$2y$10$wwTY.Z0ROcEgdQLlGAYuSOOGtEbm9JMo887OtiHQdgpV6h6LFhMqO', 1, 0, 0);
REPLACE INTO User (id, email, password, admin, isDeleted, isLoggedIn) values (2, 'admin2@conushop.com', '$2y$10$wwTY.Z0ROcEgdQLlGAYuSOOGtEbm9JMo887OtiHQdgpV6h6LFhMqO', 1,  0, 0);
REPLACE INTO User (id, email, password, admin, isDeleted, isLoggedIn) values (3, 'admin3@conushop.com', '$2y$10$wwTY.Z0ROcEgdQLlGAYuSOOGtEbm9JMo887OtiHQdgpV6h6LFhMqO', 1,  0, 0);

REPLACE INTO User (id, firstName, lastName, email, password, phone, admin, physicalAddress, isDeleted, isLoggedIn) values (4, 'First', 'Client', 'client1@conushop.com', '$2y$10$9TXiu1Zu4uxhwxrBXDOnmu6Dg3XhjXmV4hJggc/XfSQX9gObXqac6', '438-456-7890', 0, '123 Avenue H1B-4G9', 0, 0);
REPLACE INTO User (id, firstName, lastName, email, password, phone, admin, physicalAddress, isDeleted, isLoggedIn) values (5, 'Second', 'Client', 'client2@conushop.com', '$2y$10$9TXiu1Zu4uxhwxrBXDOnmu6Dg3XhjXmV4hJggc/XfSQX9gObXqac6', '514-438-4190', 0, '234 Avenue H1C-5G0', 0, 0);
REPLACE INTO User (id, firstName, lastName, email, password, phone, admin, physicalAddress, isDeleted, isLoggedIn) values (6, 'Third', 'Client', 'client3@conushop.com', '$2y$10$9TXiu1Zu4uxhwxrBXDOnmu6Dg3XhjXmV4hJggc/XfSQX9gObXqac6', '514-168-3590', 0, '345 Avenue H1D-6G1', 0, 0);

REPLACE INTO ElectronicSpecification SET id = '1', dimension = '10 x 50 x 100', weight = '10', modelNumber = '123', brandName = 'Asus', hdSize ='512', price = '1200', processorType = 'Intel i7', ramSize = '16', cpuCores = '4', os = 'Windows', ElectronicType_id = '1', image = '/images/1511129568.jpg', isDeleted = '0';
REPLACE INTO ElectronicItem SET id = '1', ElectronicSpecification_id = '1', serialNumber = '8MU4D5MQGN2I1';
REPLACE INTO ElectronicItem SET id = '2', ElectronicSpecification_id = '1', serialNumber = '8MU4D5MQGN2I2';
REPLACE INTO ElectronicItem SET id = '3', ElectronicSpecification_id = '1', serialNumber = '8MU4D5MQGN2I3';

REPLACE INTO ElectronicSpecification SET id = '2', weight = '5', modelNumber = '9560', brandName = 'Dell', hdSize = '512', price = '2500', processorType = 'Intel i7', ramSize = '16', cpuCores = '4', batteryInfo = '84 Wh', os = 'Linux', camera = '1', touchScreen = '1', ElectronicType_id = '2', displaySize = '15.0', image = '/images/1511130460.jpg', isDeleted = '0';
REPLACE INTO ElectronicItem SET id = '4', ElectronicSpecification_id = '2', serialNumber = 'GZY6DOXV5F1';
REPLACE INTO ElectronicItem SET id = '5', ElectronicSpecification_id = '2', serialNumber = 'GZY6DOXV5F2';
REPLACE INTO ElectronicItem SET id = '6', ElectronicSpecification_id = '2', serialNumber = 'GZY6DOXV5F3';

REPLACE INTO ElectronicSpecification SET id = '3', weight = '4', modelNumber = 'XR3501', brandName = 'BenQ', price = '1000', ElectronicType_id = '3', displaySize = '4.0', image = '/images/1511130961.jpg', isDeleted = '0';
REPLACE INTO ElectronicItem SET id = '7', ElectronicSpecification_id = '3', serialNumber = 'MBIX3Q7UHWSG1';
REPLACE INTO ElectronicItem SET id = '8', ElectronicSpecification_id = '3', serialNumber = 'MBIX3Q7UHWSG2';
REPLACE INTO ElectronicItem SET id = '9', ElectronicSpecification_id = '3', serialNumber = 'MBIX3Q7UHWSG3';

REPLACE INTO ElectronicSpecification SET id = '4', dimension = '13 x 10 x 1', weight = '0.5', modelNumber = 'MLPY2CLA1', brandName = 'Apple iPad Pro', hdSize = '32', price = '700.89', processorType = 'A9X chip', ramSize = '4', cpuCores = '4', batteryInfo = '10 hours', os = 'iOS 9', camera = '1', ElectronicType_id = '4', displaySize = '9.7', image = '/images/1511155838.jpg', isDeleted = '0';
REPLACE INTO ElectronicItem SET id = '10', ElectronicSpecification_id = '4', serialNumber = 'GXQSJG4SXV701';
REPLACE INTO ElectronicItem SET id = '11', ElectronicSpecification_id = '4', serialNumber = 'GXQSJG4SXV702';
REPLACE INTO ElectronicItem SET id = '12', ElectronicSpecification_id = '4', serialNumber = 'GXQSJG4SXV703';




SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
