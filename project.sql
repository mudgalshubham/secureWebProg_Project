-- MySQL Script generated by MySQL Workbench
-- Mon Apr 18 19:45:04 2016
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema projectecom
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `projectecom` ;

-- -----------------------------------------------------
-- Schema projectecom
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `projectecom` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `projectecom` ;

-- -----------------------------------------------------
-- Table `projectecom`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `projectecom`.`users` ;

CREATE TABLE IF NOT EXISTS `projectecom`.`users` (
  `userid` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `username` VARCHAR(256) NOT NULL COMMENT '',
  `email` VARCHAR(256) NOT NULL COMMENT '',
  `password` VARCHAR(256) NOT NULL COMMENT '',
  `salt` VARCHAR(64) NOT NULL COMMENT '',
  `phone` VARCHAR(12) NULL COMMENT '',
  PRIMARY KEY (`userid`)  COMMENT '',
  UNIQUE INDEX `email_UNIQUE` (`email` ASC)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projectecom`.`catalog`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `projectecom`.`catalog` ;

CREATE TABLE IF NOT EXISTS `projectecom`.`catalog` (
  `itemid` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `itemname` VARCHAR(45) NOT NULL COMMENT '',
  `price` VARCHAR(10) NOT NULL COMMENT '',
  `description` VARCHAR(1024) NULL COMMENT '',
  `picture` VARCHAR(512) NULL COMMENT '',
  `sellerid` INT NOT NULL COMMENT '',
  
  PRIMARY KEY (`itemid`)  COMMENT '',
  INDEX `catalogusers_idx` (`sellerid` ASC)  COMMENT '',
  CONSTRAINT `catalogusers`
    FOREIGN KEY (`sellerid`)
    REFERENCES `projectecom`.`users` (`userid`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projectecom`.`rating`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `projectecom`.`rating` ;

CREATE TABLE IF NOT EXISTS `projectecom`.`rating` (
  `ratingid` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `avgrating` DECIMAL(3,2) NULL COMMENT '',
  `count` INT NULL COMMENT '',
  `sellerid` INT NOT NULL COMMENT '',
  PRIMARY KEY (`ratingid`)  COMMENT '',
  INDEX `ratinguser_idx` (`sellerid` ASC)  COMMENT '',
  CONSTRAINT `ratinguser`
    FOREIGN KEY (`sellerid`)
    REFERENCES `projectecom`.`users` (`userid`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projectecom`.`reviews`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `projectecom`.`reviews` ;

CREATE TABLE IF NOT EXISTS `projectecom`.`reviews` (
  `reviewid` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `review` VARCHAR(1024) NULL COMMENT '',
  `buyerid` INT NOT NULL COMMENT '',
  `sellerid` INT NOT NULL COMMENT '',
  PRIMARY KEY (`reviewid`)  COMMENT '',
  INDEX `reviewsuser_idx` (`buyerid` ASC)  COMMENT '',
  INDEX `reviewsseller_idx` (`sellerid` ASC)  COMMENT '',
  CONSTRAINT `reviewsbuyer`
    FOREIGN KEY (`buyerid`)
    REFERENCES `projectecom`.`users` (`userid`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `reviewsseller`
    FOREIGN KEY (`sellerid`)
    REFERENCES `projectecom`.`users` (`userid`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
