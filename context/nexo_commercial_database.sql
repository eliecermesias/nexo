-- Nexo commercial database proposal
-- MySQL / MariaDB compatible DDL

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE SCHEMA IF NOT EXISTS `nexo_alt` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `nexo_alt`;

CREATE TABLE IF NOT EXISTS `document_types` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(30) NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `description` VARCHAR(255) NULL,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UK_document_types_code` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `enterprises` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `document_types_Id` BIGINT UNSIGNED NOT NULL,
  `document_number` VARCHAR(50) NOT NULL,
  `legal_name` VARCHAR(180) NOT NULL,
  `trade_name` VARCHAR(180) NULL,
  `email` VARCHAR(180) NULL,
  `phone` VARCHAR(40) NULL,
  `address` VARCHAR(255) NULL,
  `city` VARCHAR(120) NULL,
  `state` VARCHAR(120) NULL,
  `country` VARCHAR(120) NOT NULL DEFAULT 'Colombia',
  `tax_regime` VARCHAR(120) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UK_enterprises_document` (`document_types_Id`, `document_number`),
  CONSTRAINT `FK_document_types_enterprises`
    FOREIGN KEY (`document_types_Id`)
    REFERENCES `document_types` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `parties` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `document_types_Id` BIGINT UNSIGNED NOT NULL,
  `document_number` VARCHAR(50) NOT NULL,
  `party_type` ENUM('person', 'company') NOT NULL DEFAULT 'company',
  `legal_name` VARCHAR(180) NOT NULL,
  `trade_name` VARCHAR(180) NULL,
  `email` VARCHAR(180) NULL,
  `phone` VARCHAR(40) NULL,
  `address` VARCHAR(255) NULL,
  `city` VARCHAR(120) NULL,
  `state` VARCHAR(120) NULL,
  `country` VARCHAR(120) NOT NULL DEFAULT 'Colombia',
  `is_customer` BOOLEAN NOT NULL DEFAULT TRUE,
  `is_supplier` BOOLEAN NOT NULL DEFAULT FALSE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UK_parties_document` (`document_types_Id`, `document_number`),
  KEY `IDX_parties_legal_name` (`legal_name`),
  CONSTRAINT `FK_document_types_parties`
    FOREIGN KEY (`document_types_Id`)
    REFERENCES `document_types` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `contacts` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `parties_Id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(180) NOT NULL,
  `position` VARCHAR(120) NULL,
  `email` VARCHAR(180) NULL,
  `phone` VARCHAR(40) NULL,
  `is_primary` BOOLEAN NOT NULL DEFAULT FALSE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IDX_contacts_parties_Id` (`parties_Id`),
  CONSTRAINT `FK_parties_contacts`
    FOREIGN KEY (`parties_Id`)
    REFERENCES `parties` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `currencies` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` CHAR(3) NOT NULL,
  `name` VARCHAR(80) NOT NULL,
  `symbol` VARCHAR(10) NOT NULL,
  `decimal_place` TINYINT UNSIGNED NOT NULL DEFAULT 2,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UK_currencies_code` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `document_statuses` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(40) NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `description` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UK_document_statuses_code` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `services` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `name` VARCHAR(180) NOT NULL,
  `description` TEXT NULL,
  `unit` VARCHAR(40) NOT NULL DEFAULT 'unit',
  `unit_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UK_services_code` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `plans` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `name` VARCHAR(180) NOT NULL,
  `description` TEXT NULL,
  `billing_period` ENUM('one_time', 'monthly', 'quarterly', 'semiannual', 'annual') NOT NULL DEFAULT 'monthly',
  `price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UK_plans_code` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `plan_items` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `plans_Id` BIGINT UNSIGNED NOT NULL,
  `services_Id` BIGINT UNSIGNED NOT NULL,
  `quantity` DECIMAL(12,2) NOT NULL DEFAULT 1.00,
  `unit_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IDX_plan_items_plans_Id` (`plans_Id`),
  KEY `IDX_plan_items_services_Id` (`services_Id`),
  CONSTRAINT `FK_plans_plan_items`
    FOREIGN KEY (`plans_Id`)
    REFERENCES `plans` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_services_plan_items`
    FOREIGN KEY (`services_Id`)
    REFERENCES `services` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `taxes` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(40) NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UK_taxes_code` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `quotations` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `enterprises_Id` BIGINT UNSIGNED NOT NULL,
  `parties_Id` BIGINT UNSIGNED NOT NULL,
  `contacts_Id` BIGINT UNSIGNED NULL,
  `currencies_Id` BIGINT UNSIGNED NOT NULL,
  `document_statuses_Id` BIGINT UNSIGNED NOT NULL,
  `number` VARCHAR(50) NOT NULL,
  `issue_date` DATE NOT NULL,
  `valid_until` DATE NULL,
  `subtotal` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `discount_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `tax_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `term` TEXT NULL,
  `note` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UK_quotations_enterprise_number` (`enterprises_Id`, `number`),
  KEY `IDX_quotations_parties_Id` (`parties_Id`),
  KEY `IDX_quotations_contacts_Id` (`contacts_Id`),
  KEY `IDX_quotations_currencies_Id` (`currencies_Id`),
  KEY `IDX_quotations_document_statuses_Id` (`document_statuses_Id`),
  CONSTRAINT `FK_enterprises_quotations`
    FOREIGN KEY (`enterprises_Id`)
    REFERENCES `enterprises` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_parties_quotations`
    FOREIGN KEY (`parties_Id`)
    REFERENCES `parties` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_contacts_quotations`
    FOREIGN KEY (`contacts_Id`)
    REFERENCES `contacts` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_currencies_quotations`
    FOREIGN KEY (`currencies_Id`)
    REFERENCES `currencies` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_document_statuses_quotations`
    FOREIGN KEY (`document_statuses_Id`)
    REFERENCES `document_statuses` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `quotation_items` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `quotations_Id` BIGINT UNSIGNED NOT NULL,
  `services_Id` BIGINT UNSIGNED NULL,
  `plans_Id` BIGINT UNSIGNED NULL,
  `taxes_Id` BIGINT UNSIGNED NULL,
  `description` TEXT NOT NULL,
  `quantity` DECIMAL(12,2) NOT NULL DEFAULT 1.00,
  `unit_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `discount_rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `tax_rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `line_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IDX_quotation_items_quotations_Id` (`quotations_Id`),
  KEY `IDX_quotation_items_services_Id` (`services_Id`),
  KEY `IDX_quotation_items_plans_Id` (`plans_Id`),
  KEY `IDX_quotation_items_taxes_Id` (`taxes_Id`),
  CONSTRAINT `FK_quotations_quotation_items`
    FOREIGN KEY (`quotations_Id`)
    REFERENCES `quotations` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_services_quotation_items`
    FOREIGN KEY (`services_Id`)
    REFERENCES `services` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_plans_quotation_items`
    FOREIGN KEY (`plans_Id`)
    REFERENCES `plans` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_taxes_quotation_items`
    FOREIGN KEY (`taxes_Id`)
    REFERENCES `taxes` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `proposals` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `quotations_Id` BIGINT UNSIGNED NULL,
  `enterprises_Id` BIGINT UNSIGNED NOT NULL,
  `parties_Id` BIGINT UNSIGNED NOT NULL,
  `contacts_Id` BIGINT UNSIGNED NULL,
  `currencies_Id` BIGINT UNSIGNED NOT NULL,
  `document_statuses_Id` BIGINT UNSIGNED NOT NULL,
  `number` VARCHAR(50) NOT NULL,
  `title` VARCHAR(180) NOT NULL,
  `issue_date` DATE NOT NULL,
  `valid_until` DATE NULL,
  `subtotal` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `discount_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `tax_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `scope` TEXT NULL,
  `term` TEXT NULL,
  `note` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UK_proposals_enterprise_number` (`enterprises_Id`, `number`),
  KEY `IDX_proposals_quotations_Id` (`quotations_Id`),
  KEY `IDX_proposals_parties_Id` (`parties_Id`),
  KEY `IDX_proposals_contacts_Id` (`contacts_Id`),
  KEY `IDX_proposals_currencies_Id` (`currencies_Id`),
  KEY `IDX_proposals_document_statuses_Id` (`document_statuses_Id`),
  CONSTRAINT `FK_quotations_proposals`
    FOREIGN KEY (`quotations_Id`)
    REFERENCES `quotations` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_enterprises_proposals`
    FOREIGN KEY (`enterprises_Id`)
    REFERENCES `enterprises` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_parties_proposals`
    FOREIGN KEY (`parties_Id`)
    REFERENCES `parties` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_contacts_proposals`
    FOREIGN KEY (`contacts_Id`)
    REFERENCES `contacts` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_currencies_proposals`
    FOREIGN KEY (`currencies_Id`)
    REFERENCES `currencies` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_document_statuses_proposals`
    FOREIGN KEY (`document_statuses_Id`)
    REFERENCES `document_statuses` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `proposal_items` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposals_Id` BIGINT UNSIGNED NOT NULL,
  `services_Id` BIGINT UNSIGNED NULL,
  `plans_Id` BIGINT UNSIGNED NULL,
  `taxes_Id` BIGINT UNSIGNED NULL,
  `description` TEXT NOT NULL,
  `quantity` DECIMAL(12,2) NOT NULL DEFAULT 1.00,
  `unit_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `discount_rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `tax_rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `line_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IDX_proposal_items_proposals_Id` (`proposals_Id`),
  KEY `IDX_proposal_items_services_Id` (`services_Id`),
  KEY `IDX_proposal_items_plans_Id` (`plans_Id`),
  KEY `IDX_proposal_items_taxes_Id` (`taxes_Id`),
  CONSTRAINT `FK_proposals_proposal_items`
    FOREIGN KEY (`proposals_Id`)
    REFERENCES `proposals` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_services_proposal_items`
    FOREIGN KEY (`services_Id`)
    REFERENCES `services` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_plans_proposal_items`
    FOREIGN KEY (`plans_Id`)
    REFERENCES `plans` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_taxes_proposal_items`
    FOREIGN KEY (`taxes_Id`)
    REFERENCES `taxes` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `collection_accounts` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposals_Id` BIGINT UNSIGNED NULL,
  `enterprises_Id` BIGINT UNSIGNED NOT NULL,
  `parties_Id` BIGINT UNSIGNED NOT NULL,
  `contacts_Id` BIGINT UNSIGNED NULL,
  `currencies_Id` BIGINT UNSIGNED NOT NULL,
  `document_statuses_Id` BIGINT UNSIGNED NOT NULL,
  `number` VARCHAR(50) NOT NULL,
  `issue_date` DATE NOT NULL,
  `due_date` DATE NULL,
  `subtotal` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `discount_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `tax_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `paid_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `balance` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `concept` TEXT NULL,
  `note` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UK_collection_accounts_enterprise_number` (`enterprises_Id`, `number`),
  KEY `IDX_collection_accounts_proposals_Id` (`proposals_Id`),
  KEY `IDX_collection_accounts_parties_Id` (`parties_Id`),
  KEY `IDX_collection_accounts_contacts_Id` (`contacts_Id`),
  KEY `IDX_collection_accounts_currencies_Id` (`currencies_Id`),
  KEY `IDX_collection_accounts_document_statuses_Id` (`document_statuses_Id`),
  CONSTRAINT `FK_proposals_collection_accounts`
    FOREIGN KEY (`proposals_Id`)
    REFERENCES `proposals` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_enterprises_collection_accounts`
    FOREIGN KEY (`enterprises_Id`)
    REFERENCES `enterprises` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_parties_collection_accounts`
    FOREIGN KEY (`parties_Id`)
    REFERENCES `parties` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_contacts_collection_accounts`
    FOREIGN KEY (`contacts_Id`)
    REFERENCES `contacts` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_currencies_collection_accounts`
    FOREIGN KEY (`currencies_Id`)
    REFERENCES `currencies` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_document_statuses_collection_accounts`
    FOREIGN KEY (`document_statuses_Id`)
    REFERENCES `document_statuses` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `collection_account_items` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `collection_accounts_Id` BIGINT UNSIGNED NOT NULL,
  `services_Id` BIGINT UNSIGNED NULL,
  `plans_Id` BIGINT UNSIGNED NULL,
  `taxes_Id` BIGINT UNSIGNED NULL,
  `description` TEXT NOT NULL,
  `quantity` DECIMAL(12,2) NOT NULL DEFAULT 1.00,
  `unit_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `discount_rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `tax_rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `line_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IDX_collection_account_items_collection_accounts_Id` (`collection_accounts_Id`),
  KEY `IDX_collection_account_items_services_Id` (`services_Id`),
  KEY `IDX_collection_account_items_plans_Id` (`plans_Id`),
  KEY `IDX_collection_account_items_taxes_Id` (`taxes_Id`),
  CONSTRAINT `FK_collection_accounts_collection_account_items`
    FOREIGN KEY (`collection_accounts_Id`)
    REFERENCES `collection_accounts` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_services_collection_account_items`
    FOREIGN KEY (`services_Id`)
    REFERENCES `services` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_plans_collection_account_items`
    FOREIGN KEY (`plans_Id`)
    REFERENCES `plans` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_taxes_collection_account_items`
    FOREIGN KEY (`taxes_Id`)
    REFERENCES `taxes` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `invoices` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposals_Id` BIGINT UNSIGNED NULL,
  `collection_accounts_Id` BIGINT UNSIGNED NULL,
  `enterprises_Id` BIGINT UNSIGNED NOT NULL,
  `parties_Id` BIGINT UNSIGNED NOT NULL,
  `contacts_Id` BIGINT UNSIGNED NULL,
  `currencies_Id` BIGINT UNSIGNED NOT NULL,
  `document_statuses_Id` BIGINT UNSIGNED NOT NULL,
  `number` VARCHAR(50) NOT NULL,
  `authorization_number` VARCHAR(120) NULL,
  `issue_date` DATE NOT NULL,
  `due_date` DATE NULL,
  `subtotal` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `discount_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `tax_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `paid_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `balance` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `note` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UK_invoices_enterprise_number` (`enterprises_Id`, `number`),
  KEY `IDX_invoices_proposals_Id` (`proposals_Id`),
  KEY `IDX_invoices_collection_accounts_Id` (`collection_accounts_Id`),
  KEY `IDX_invoices_parties_Id` (`parties_Id`),
  KEY `IDX_invoices_contacts_Id` (`contacts_Id`),
  KEY `IDX_invoices_currencies_Id` (`currencies_Id`),
  KEY `IDX_invoices_document_statuses_Id` (`document_statuses_Id`),
  CONSTRAINT `FK_proposals_invoices`
    FOREIGN KEY (`proposals_Id`)
    REFERENCES `proposals` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_collection_accounts_invoices`
    FOREIGN KEY (`collection_accounts_Id`)
    REFERENCES `collection_accounts` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_enterprises_invoices`
    FOREIGN KEY (`enterprises_Id`)
    REFERENCES `enterprises` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_parties_invoices`
    FOREIGN KEY (`parties_Id`)
    REFERENCES `parties` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_contacts_invoices`
    FOREIGN KEY (`contacts_Id`)
    REFERENCES `contacts` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_currencies_invoices`
    FOREIGN KEY (`currencies_Id`)
    REFERENCES `currencies` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_document_statuses_invoices`
    FOREIGN KEY (`document_statuses_Id`)
    REFERENCES `document_statuses` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `invoice_items` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoices_Id` BIGINT UNSIGNED NOT NULL,
  `services_Id` BIGINT UNSIGNED NULL,
  `plans_Id` BIGINT UNSIGNED NULL,
  `taxes_Id` BIGINT UNSIGNED NULL,
  `description` TEXT NOT NULL,
  `quantity` DECIMAL(12,2) NOT NULL DEFAULT 1.00,
  `unit_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `discount_rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `tax_rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `line_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IDX_invoice_items_invoices_Id` (`invoices_Id`),
  KEY `IDX_invoice_items_services_Id` (`services_Id`),
  KEY `IDX_invoice_items_plans_Id` (`plans_Id`),
  KEY `IDX_invoice_items_taxes_Id` (`taxes_Id`),
  CONSTRAINT `FK_invoices_invoice_items`
    FOREIGN KEY (`invoices_Id`)
    REFERENCES `invoices` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_services_invoice_items`
    FOREIGN KEY (`services_Id`)
    REFERENCES `services` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_plans_invoice_items`
    FOREIGN KEY (`plans_Id`)
    REFERENCES `plans` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_taxes_invoice_items`
    FOREIGN KEY (`taxes_Id`)
    REFERENCES `taxes` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `payment_methods` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(40) NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `requires_bank_account` BOOLEAN NOT NULL DEFAULT FALSE,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UK_payment_methods_code` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `banks` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(40) NULL,
  `name` VARCHAR(180) NOT NULL,
  `country` VARCHAR(120) NOT NULL DEFAULT 'Colombia',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UK_banks_code` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `bank_accounts` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `enterprises_Id` BIGINT UNSIGNED NOT NULL,
  `banks_Id` BIGINT UNSIGNED NOT NULL,
  `currencies_Id` BIGINT UNSIGNED NOT NULL,
  `account_type` ENUM('checking', 'savings', 'current', 'other') NOT NULL DEFAULT 'savings',
  `account_number` VARCHAR(80) NOT NULL,
  `account_holder` VARCHAR(180) NOT NULL,
  `swift_code` VARCHAR(40) NULL,
  `routing_number` VARCHAR(40) NULL,
  `is_default` BOOLEAN NOT NULL DEFAULT FALSE,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UK_bank_accounts_enterprise_number` (`enterprises_Id`, `account_number`),
  KEY `IDX_bank_accounts_banks_Id` (`banks_Id`),
  KEY `IDX_bank_accounts_currencies_Id` (`currencies_Id`),
  CONSTRAINT `FK_enterprises_bank_accounts`
    FOREIGN KEY (`enterprises_Id`)
    REFERENCES `enterprises` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_banks_bank_accounts`
    FOREIGN KEY (`banks_Id`)
    REFERENCES `banks` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_currencies_bank_accounts`
    FOREIGN KEY (`currencies_Id`)
    REFERENCES `currencies` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `payment_destinations` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `enterprises_Id` BIGINT UNSIGNED NOT NULL,
  `payment_methods_Id` BIGINT UNSIGNED NOT NULL,
  `bank_accounts_Id` BIGINT UNSIGNED NULL,
  `name` VARCHAR(180) NOT NULL,
  `cash_location` VARCHAR(180) NULL,
  `check_payee_name` VARCHAR(180) NULL,
  `instruction` TEXT NULL,
  `is_default` BOOLEAN NOT NULL DEFAULT FALSE,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IDX_payment_destinations_enterprises_Id` (`enterprises_Id`),
  KEY `IDX_payment_destinations_payment_methods_Id` (`payment_methods_Id`),
  KEY `IDX_payment_destinations_bank_accounts_Id` (`bank_accounts_Id`),
  CONSTRAINT `FK_enterprises_payment_destinations`
    FOREIGN KEY (`enterprises_Id`)
    REFERENCES `enterprises` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_payment_methods_payment_destinations`
    FOREIGN KEY (`payment_methods_Id`)
    REFERENCES `payment_methods` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_bank_accounts_payment_destinations`
    FOREIGN KEY (`bank_accounts_Id`)
    REFERENCES `bank_accounts` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `payments` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_methods_Id` BIGINT UNSIGNED NOT NULL,
  `payment_destinations_Id` BIGINT UNSIGNED NOT NULL,
  `collection_accounts_Id` BIGINT UNSIGNED NULL,
  `invoices_Id` BIGINT UNSIGNED NULL,
  `currencies_Id` BIGINT UNSIGNED NOT NULL,
  `reference` VARCHAR(120) NULL,
  `paid_at` DATETIME NOT NULL,
  `amount` DECIMAL(15,2) NOT NULL,
  `payer_name` VARCHAR(180) NULL,
  `note` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IDX_payments_payment_methods_Id` (`payment_methods_Id`),
  KEY `IDX_payments_payment_destinations_Id` (`payment_destinations_Id`),
  KEY `IDX_payments_collection_accounts_Id` (`collection_accounts_Id`),
  KEY `IDX_payments_invoices_Id` (`invoices_Id`),
  KEY `IDX_payments_currencies_Id` (`currencies_Id`),
  CONSTRAINT `FK_payment_methods_payments`
    FOREIGN KEY (`payment_methods_Id`)
    REFERENCES `payment_methods` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_payment_destinations_payments`
    FOREIGN KEY (`payment_destinations_Id`)
    REFERENCES `payment_destinations` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_collection_accounts_payments`
    FOREIGN KEY (`collection_accounts_Id`)
    REFERENCES `collection_accounts` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_invoices_payments`
    FOREIGN KEY (`invoices_Id`)
    REFERENCES `invoices` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_currencies_payments`
    FOREIGN KEY (`currencies_Id`)
    REFERENCES `currencies` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `CK_payments_single_payable`
    CHECK (
      (`collection_accounts_Id` IS NOT NULL AND `invoices_Id` IS NULL)
      OR (`collection_accounts_Id` IS NULL AND `invoices_Id` IS NOT NULL)
    )
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `document_templates` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `enterprises_Id` BIGINT UNSIGNED NOT NULL,
  `document_kind` ENUM('quotation', 'proposal', 'collection_account', 'invoice') NOT NULL,
  `name` VARCHAR(180) NOT NULL,
  `description` VARCHAR(255) NULL,
  `is_default` BOOLEAN NOT NULL DEFAULT FALSE,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IDX_document_templates_enterprises_Id` (`enterprises_Id`),
  CONSTRAINT `FK_enterprises_document_templates`
    FOREIGN KEY (`enterprises_Id`)
    REFERENCES `enterprises` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `document_template_versions` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `document_templates_Id` BIGINT UNSIGNED NOT NULL,
  `version` INT UNSIGNED NOT NULL DEFAULT 1,
  `content` LONGTEXT NOT NULL,
  `metadata` JSON NULL,
  `is_published` BOOLEAN NOT NULL DEFAULT FALSE,
  `published_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UK_document_template_versions_template_version` (`document_templates_Id`, `version`),
  CONSTRAINT `FK_document_templates_document_template_versions`
    FOREIGN KEY (`document_templates_Id`)
    REFERENCES `document_templates` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `collection_account_attachments` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `collection_accounts_Id` BIGINT UNSIGNED NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(500) NOT NULL,
  `mime_type` VARCHAR(120) NOT NULL,
  `file_size` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `description` VARCHAR(255) NULL,
  `uploaded_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IDX_collection_account_attachments_collection_accounts_Id` (`collection_accounts_Id`),
  CONSTRAINT `FK_collection_accounts_collection_account_attachments`
    FOREIGN KEY (`collection_accounts_Id`)
    REFERENCES `collection_accounts` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `invoice_attachments` (
  `Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoices_Id` BIGINT UNSIGNED NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(500) NOT NULL,
  `mime_type` VARCHAR(120) NOT NULL,
  `file_size` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `description` VARCHAR(255) NULL,
  `uploaded_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IDX_invoice_attachments_invoices_Id` (`invoices_Id`),
  CONSTRAINT `FK_invoices_invoice_attachments`
    FOREIGN KEY (`invoices_Id`)
    REFERENCES `invoices` (`Id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT
) ENGINE=InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
