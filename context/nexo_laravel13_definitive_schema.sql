-- Nexo definitive working schema
-- Laravel 13 / MySQL / MariaDB compatible DDL
-- This schema combines the current migrations, seeders, factories domain,
-- and the commercial modules required by the Nexo system.

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE SCHEMA IF NOT EXISTS `nexo_v2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `nexo_v2`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `current_team_id` BIGINT UNSIGNED NULL,
  `two_factor_secret` TEXT NULL,
  `two_factor_recovery_codes` TEXT NULL,
  `two_factor_confirmed_at` TIMESTAMP NULL DEFAULT NULL,
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_current_team_id_foreign` (`current_team_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` TINYINT UNSIGNED NOT NULL,
  `reserved_at` INT UNSIGNED NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` MEDIUMTEXT NULL,
  `cancelled_at` INT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `teams` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `is_personal` BOOLEAN NOT NULL DEFAULT FALSE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teams_slug_unique` (`slug`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `team_members` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `team_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `role` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_members_team_id_user_id_unique` (`team_id`, `user_id`),
  KEY `team_members_user_id_foreign` (`user_id`),
  CONSTRAINT `team_members_team_id_foreign`
    FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`)
    ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `team_members_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `team_invitations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(64) NOT NULL,
  `team_id` BIGINT UNSIGNED NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `role` VARCHAR(255) NOT NULL,
  `invited_by` BIGINT UNSIGNED NOT NULL,
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  `accepted_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_invitations_code_unique` (`code`),
  KEY `team_invitations_team_id_foreign` (`team_id`),
  KEY `team_invitations_invited_by_foreign` (`invited_by`),
  CONSTRAINT `team_invitations_team_id_foreign`
    FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`)
    ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `team_invitations_invited_by_foreign`
    FOREIGN KEY (`invited_by`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB;

ALTER TABLE `users`
  ADD CONSTRAINT `users_current_team_id_foreign`
    FOREIGN KEY (`current_team_id`) REFERENCES `teams` (`id`)
    ON DELETE SET NULL ON UPDATE RESTRICT;

CREATE TABLE IF NOT EXISTS `menus` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_id` BIGINT UNSIGNED NULL,
  `name` VARCHAR(255) NOT NULL,
  `icon` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `current` VARCHAR(255) NULL,
  `priority` INT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menus_menu_id_foreign` (`menu_id`),
  CONSTRAINT `menus_menu_id_foreign`
    FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `document_types` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(30) NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `description` VARCHAR(255) NULL,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_types_code_unique` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `document_classes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(40) NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `description` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_classes_code_unique` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `document_statuses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(40) NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `description` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_statuses_code_unique` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `currencies` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` CHAR(3) NOT NULL,
  `name` VARCHAR(80) NOT NULL,
  `symbol` VARCHAR(10) NOT NULL,
  `decimal_place` TINYINT UNSIGNED NOT NULL DEFAULT 2,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `currencies_code_unique` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `enterprises` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `document_type_id` BIGINT UNSIGNED NULL,
  `document_number` VARCHAR(50) NULL,
  `name` VARCHAR(180) NOT NULL,
  `trade_name` VARCHAR(180) NULL,
  `phone` VARCHAR(40) NULL,
  `email` VARCHAR(180) NULL,
  `address` VARCHAR(255) NULL,
  `website` VARCHAR(255) NULL,
  `city` VARCHAR(120) NULL,
  `state` VARCHAR(120) NULL,
  `country` VARCHAR(120) NOT NULL DEFAULT 'Colombia',
  `tax_regime` VARCHAR(120) NULL,
  `is_issuer` BOOLEAN NOT NULL DEFAULT FALSE,
  `is_customer` BOOLEAN NOT NULL DEFAULT TRUE,
  `is_supplier` BOOLEAN NOT NULL DEFAULT FALSE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `enterprises_document_type_id_document_number_unique` (`document_type_id`, `document_number`),
  KEY `enterprises_name_index` (`name`),
  CONSTRAINT `enterprises_document_type_id_foreign`
    FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `people` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `document_type_id` BIGINT UNSIGNED NOT NULL,
  `document_number` VARCHAR(50) NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `lastname` VARCHAR(120) NULL,
  `email` VARCHAR(180) NULL,
  `phone` VARCHAR(40) NULL,
  `address` VARCHAR(255) NULL,
  `city` VARCHAR(120) NULL,
  `state` VARCHAR(120) NULL,
  `country` VARCHAR(120) NOT NULL DEFAULT 'Colombia',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `people_document_type_id_document_number_unique` (`document_type_id`, `document_number`),
  UNIQUE KEY `people_email_unique` (`email`),
  CONSTRAINT `people_document_type_id_foreign`
    FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `enterprise_id` BIGINT UNSIGNED NOT NULL,
  `person_id` BIGINT UNSIGNED NULL,
  `name` VARCHAR(180) NOT NULL,
  `position` VARCHAR(120) NULL,
  `email` VARCHAR(180) NULL,
  `phone` VARCHAR(40) NULL,
  `is_primary` BOOLEAN NOT NULL DEFAULT FALSE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contacts_enterprise_id_foreign` (`enterprise_id`),
  KEY `contacts_person_id_foreign` (`person_id`),
  CONSTRAINT `contacts_enterprise_id_foreign`
    FOREIGN KEY (`enterprise_id`) REFERENCES `enterprises` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `contacts_person_id_foreign`
    FOREIGN KEY (`person_id`) REFERENCES `people` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `services` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `name` VARCHAR(180) NOT NULL,
  `description` TEXT NULL,
  `unit` VARCHAR(40) NOT NULL DEFAULT 'unit',
  `unit_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_code_unique` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `plans` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `name` VARCHAR(180) NOT NULL,
  `description` TEXT NULL,
  `billing_period` ENUM('one_time', 'monthly', 'quarterly', 'semiannual', 'annual') NOT NULL DEFAULT 'monthly',
  `price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `plans_code_unique` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `plan_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `plan_id` BIGINT UNSIGNED NOT NULL,
  `service_id` BIGINT UNSIGNED NOT NULL,
  `quantity` DECIMAL(12,2) NOT NULL DEFAULT 1.00,
  `unit_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plan_items_plan_id_foreign` (`plan_id`),
  KEY `plan_items_service_id_foreign` (`service_id`),
  CONSTRAINT `plan_items_plan_id_foreign`
    FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `plan_items_service_id_foreign`
    FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `taxes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(40) NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `taxes_code_unique` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `quotations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `issuer_enterprise_id` BIGINT UNSIGNED NOT NULL,
  `customer_enterprise_id` BIGINT UNSIGNED NOT NULL,
  `contact_id` BIGINT UNSIGNED NULL,
  `currency_id` BIGINT UNSIGNED NOT NULL,
  `document_status_id` BIGINT UNSIGNED NOT NULL,
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `quotations_issuer_enterprise_id_number_unique` (`issuer_enterprise_id`, `number`),
  KEY `quotations_customer_enterprise_id_foreign` (`customer_enterprise_id`),
  KEY `quotations_contact_id_foreign` (`contact_id`),
  KEY `quotations_currency_id_foreign` (`currency_id`),
  KEY `quotations_document_status_id_foreign` (`document_status_id`),
  CONSTRAINT `quotations_issuer_enterprise_id_foreign`
    FOREIGN KEY (`issuer_enterprise_id`) REFERENCES `enterprises` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `quotations_customer_enterprise_id_foreign`
    FOREIGN KEY (`customer_enterprise_id`) REFERENCES `enterprises` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `quotations_contact_id_foreign`
    FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `quotations_currency_id_foreign`
    FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `quotations_document_status_id_foreign`
    FOREIGN KEY (`document_status_id`) REFERENCES `document_statuses` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `quotation_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `quotation_id` BIGINT UNSIGNED NOT NULL,
  `service_id` BIGINT UNSIGNED NULL,
  `plan_id` BIGINT UNSIGNED NULL,
  `tax_id` BIGINT UNSIGNED NULL,
  `description` TEXT NOT NULL,
  `quantity` DECIMAL(12,2) NOT NULL DEFAULT 1.00,
  `unit_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `discount_rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `tax_rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `line_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quotation_items_quotation_id_foreign` (`quotation_id`),
  KEY `quotation_items_service_id_foreign` (`service_id`),
  KEY `quotation_items_plan_id_foreign` (`plan_id`),
  KEY `quotation_items_tax_id_foreign` (`tax_id`),
  CONSTRAINT `quotation_items_quotation_id_foreign`
    FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `quotation_items_service_id_foreign`
    FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `quotation_items_plan_id_foreign`
    FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `quotation_items_tax_id_foreign`
    FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `proposals` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `quotation_id` BIGINT UNSIGNED NULL,
  `issuer_enterprise_id` BIGINT UNSIGNED NOT NULL,
  `customer_enterprise_id` BIGINT UNSIGNED NOT NULL,
  `contact_id` BIGINT UNSIGNED NULL,
  `currency_id` BIGINT UNSIGNED NOT NULL,
  `document_status_id` BIGINT UNSIGNED NOT NULL,
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `proposals_issuer_enterprise_id_number_unique` (`issuer_enterprise_id`, `number`),
  KEY `proposals_quotation_id_foreign` (`quotation_id`),
  KEY `proposals_customer_enterprise_id_foreign` (`customer_enterprise_id`),
  KEY `proposals_contact_id_foreign` (`contact_id`),
  KEY `proposals_currency_id_foreign` (`currency_id`),
  KEY `proposals_document_status_id_foreign` (`document_status_id`),
  CONSTRAINT `proposals_quotation_id_foreign`
    FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `proposals_issuer_enterprise_id_foreign`
    FOREIGN KEY (`issuer_enterprise_id`) REFERENCES `enterprises` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `proposals_customer_enterprise_id_foreign`
    FOREIGN KEY (`customer_enterprise_id`) REFERENCES `enterprises` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `proposals_contact_id_foreign`
    FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `proposals_currency_id_foreign`
    FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `proposals_document_status_id_foreign`
    FOREIGN KEY (`document_status_id`) REFERENCES `document_statuses` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `proposal_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_id` BIGINT UNSIGNED NOT NULL,
  `service_id` BIGINT UNSIGNED NULL,
  `plan_id` BIGINT UNSIGNED NULL,
  `tax_id` BIGINT UNSIGNED NULL,
  `description` TEXT NOT NULL,
  `quantity` DECIMAL(12,2) NOT NULL DEFAULT 1.00,
  `unit_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `discount_rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `tax_rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `line_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proposal_items_proposal_id_foreign` (`proposal_id`),
  KEY `proposal_items_service_id_foreign` (`service_id`),
  KEY `proposal_items_plan_id_foreign` (`plan_id`),
  KEY `proposal_items_tax_id_foreign` (`tax_id`),
  CONSTRAINT `proposal_items_proposal_id_foreign`
    FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `proposal_items_service_id_foreign`
    FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `proposal_items_plan_id_foreign`
    FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `proposal_items_tax_id_foreign`
    FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `collection_accounts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_id` BIGINT UNSIGNED NULL,
  `issuer_enterprise_id` BIGINT UNSIGNED NOT NULL,
  `customer_enterprise_id` BIGINT UNSIGNED NOT NULL,
  `contact_id` BIGINT UNSIGNED NULL,
  `currency_id` BIGINT UNSIGNED NOT NULL,
  `document_status_id` BIGINT UNSIGNED NOT NULL,
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `collection_accounts_issuer_enterprise_id_number_unique` (`issuer_enterprise_id`, `number`),
  KEY `collection_accounts_proposal_id_foreign` (`proposal_id`),
  KEY `collection_accounts_customer_enterprise_id_foreign` (`customer_enterprise_id`),
  KEY `collection_accounts_contact_id_foreign` (`contact_id`),
  KEY `collection_accounts_currency_id_foreign` (`currency_id`),
  KEY `collection_accounts_document_status_id_foreign` (`document_status_id`),
  CONSTRAINT `collection_accounts_proposal_id_foreign`
    FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `collection_accounts_issuer_enterprise_id_foreign`
    FOREIGN KEY (`issuer_enterprise_id`) REFERENCES `enterprises` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `collection_accounts_customer_enterprise_id_foreign`
    FOREIGN KEY (`customer_enterprise_id`) REFERENCES `enterprises` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `collection_accounts_contact_id_foreign`
    FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `collection_accounts_currency_id_foreign`
    FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `collection_accounts_document_status_id_foreign`
    FOREIGN KEY (`document_status_id`) REFERENCES `document_statuses` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `collection_account_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `collection_account_id` BIGINT UNSIGNED NOT NULL,
  `service_id` BIGINT UNSIGNED NULL,
  `plan_id` BIGINT UNSIGNED NULL,
  `tax_id` BIGINT UNSIGNED NULL,
  `description` TEXT NOT NULL,
  `quantity` DECIMAL(12,2) NOT NULL DEFAULT 1.00,
  `unit_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `discount_rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `tax_rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `line_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collection_account_items_collection_account_id_foreign` (`collection_account_id`),
  KEY `collection_account_items_service_id_foreign` (`service_id`),
  KEY `collection_account_items_plan_id_foreign` (`plan_id`),
  KEY `collection_account_items_tax_id_foreign` (`tax_id`),
  CONSTRAINT `collection_account_items_collection_account_id_foreign`
    FOREIGN KEY (`collection_account_id`) REFERENCES `collection_accounts` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `collection_account_items_service_id_foreign`
    FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `collection_account_items_plan_id_foreign`
    FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `collection_account_items_tax_id_foreign`
    FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `invoices` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `proposal_id` BIGINT UNSIGNED NULL,
  `collection_account_id` BIGINT UNSIGNED NULL,
  `issuer_enterprise_id` BIGINT UNSIGNED NOT NULL,
  `customer_enterprise_id` BIGINT UNSIGNED NOT NULL,
  `contact_id` BIGINT UNSIGNED NULL,
  `currency_id` BIGINT UNSIGNED NOT NULL,
  `document_status_id` BIGINT UNSIGNED NOT NULL,
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_issuer_enterprise_id_number_unique` (`issuer_enterprise_id`, `number`),
  KEY `invoices_proposal_id_foreign` (`proposal_id`),
  KEY `invoices_collection_account_id_foreign` (`collection_account_id`),
  KEY `invoices_customer_enterprise_id_foreign` (`customer_enterprise_id`),
  KEY `invoices_contact_id_foreign` (`contact_id`),
  KEY `invoices_currency_id_foreign` (`currency_id`),
  KEY `invoices_document_status_id_foreign` (`document_status_id`),
  CONSTRAINT `invoices_proposal_id_foreign`
    FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `invoices_collection_account_id_foreign`
    FOREIGN KEY (`collection_account_id`) REFERENCES `collection_accounts` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `invoices_issuer_enterprise_id_foreign`
    FOREIGN KEY (`issuer_enterprise_id`) REFERENCES `enterprises` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `invoices_customer_enterprise_id_foreign`
    FOREIGN KEY (`customer_enterprise_id`) REFERENCES `enterprises` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `invoices_contact_id_foreign`
    FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `invoices_currency_id_foreign`
    FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `invoices_document_status_id_foreign`
    FOREIGN KEY (`document_status_id`) REFERENCES `document_statuses` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `invoice_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` BIGINT UNSIGNED NOT NULL,
  `service_id` BIGINT UNSIGNED NULL,
  `plan_id` BIGINT UNSIGNED NULL,
  `tax_id` BIGINT UNSIGNED NULL,
  `description` TEXT NOT NULL,
  `quantity` DECIMAL(12,2) NOT NULL DEFAULT 1.00,
  `unit_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `discount_rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `tax_rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
  `line_total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  KEY `invoice_items_service_id_foreign` (`service_id`),
  KEY `invoice_items_plan_id_foreign` (`plan_id`),
  KEY `invoice_items_tax_id_foreign` (`tax_id`),
  CONSTRAINT `invoice_items_invoice_id_foreign`
    FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `invoice_items_service_id_foreign`
    FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `invoice_items_plan_id_foreign`
    FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `invoice_items_tax_id_foreign`
    FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `payment_methods` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(40) NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `requires_bank_account` BOOLEAN NOT NULL DEFAULT FALSE,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_methods_code_unique` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `banks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(40) NULL,
  `name` VARCHAR(180) NOT NULL,
  `country` VARCHAR(120) NOT NULL DEFAULT 'Colombia',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `banks_code_unique` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `bank_accounts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `enterprise_id` BIGINT UNSIGNED NOT NULL,
  `bank_id` BIGINT UNSIGNED NOT NULL,
  `currency_id` BIGINT UNSIGNED NOT NULL,
  `account_type` ENUM('checking', 'savings', 'current', 'other') NOT NULL DEFAULT 'savings',
  `account_number` VARCHAR(80) NOT NULL,
  `account_holder` VARCHAR(180) NOT NULL,
  `swift_code` VARCHAR(40) NULL,
  `routing_number` VARCHAR(40) NULL,
  `is_default` BOOLEAN NOT NULL DEFAULT FALSE,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bank_accounts_enterprise_id_account_number_unique` (`enterprise_id`, `account_number`),
  KEY `bank_accounts_bank_id_foreign` (`bank_id`),
  KEY `bank_accounts_currency_id_foreign` (`currency_id`),
  CONSTRAINT `bank_accounts_enterprise_id_foreign`
    FOREIGN KEY (`enterprise_id`) REFERENCES `enterprises` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `bank_accounts_bank_id_foreign`
    FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `bank_accounts_currency_id_foreign`
    FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `payment_destinations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `enterprise_id` BIGINT UNSIGNED NOT NULL,
  `payment_method_id` BIGINT UNSIGNED NOT NULL,
  `bank_account_id` BIGINT UNSIGNED NULL,
  `name` VARCHAR(180) NOT NULL,
  `cash_location` VARCHAR(180) NULL,
  `check_payee_name` VARCHAR(180) NULL,
  `instruction` TEXT NULL,
  `is_default` BOOLEAN NOT NULL DEFAULT FALSE,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_destinations_enterprise_id_foreign` (`enterprise_id`),
  KEY `payment_destinations_payment_method_id_foreign` (`payment_method_id`),
  KEY `payment_destinations_bank_account_id_foreign` (`bank_account_id`),
  CONSTRAINT `payment_destinations_enterprise_id_foreign`
    FOREIGN KEY (`enterprise_id`) REFERENCES `enterprises` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payment_destinations_payment_method_id_foreign`
    FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payment_destinations_bank_account_id_foreign`
    FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `payments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_method_id` BIGINT UNSIGNED NOT NULL,
  `payment_destination_id` BIGINT UNSIGNED NOT NULL,
  `collection_account_id` BIGINT UNSIGNED NULL,
  `invoice_id` BIGINT UNSIGNED NULL,
  `currency_id` BIGINT UNSIGNED NOT NULL,
  `reference` VARCHAR(120) NULL,
  `paid_at` DATETIME NOT NULL,
  `amount` DECIMAL(15,2) NOT NULL,
  `payer_name` VARCHAR(180) NULL,
  `note` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_payment_method_id_foreign` (`payment_method_id`),
  KEY `payments_payment_destination_id_foreign` (`payment_destination_id`),
  KEY `payments_collection_account_id_foreign` (`collection_account_id`),
  KEY `payments_invoice_id_foreign` (`invoice_id`),
  KEY `payments_currency_id_foreign` (`currency_id`),
  CONSTRAINT `payments_payment_method_id_foreign`
    FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payments_payment_destination_id_foreign`
    FOREIGN KEY (`payment_destination_id`) REFERENCES `payment_destinations` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payments_collection_account_id_foreign`
    FOREIGN KEY (`collection_account_id`) REFERENCES `collection_accounts` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payments_invoice_id_foreign`
    FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payments_currency_id_foreign`
    FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payments_single_payable_check`
    CHECK (
      (`collection_account_id` IS NOT NULL AND `invoice_id` IS NULL)
      OR (`collection_account_id` IS NULL AND `invoice_id` IS NOT NULL)
    )
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `document_templates` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `enterprise_id` BIGINT UNSIGNED NOT NULL,
  `document_class_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(180) NOT NULL,
  `description` VARCHAR(255) NULL,
  `is_default` BOOLEAN NOT NULL DEFAULT FALSE,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_templates_enterprise_id_foreign` (`enterprise_id`),
  KEY `document_templates_document_class_id_foreign` (`document_class_id`),
  CONSTRAINT `document_templates_enterprise_id_foreign`
    FOREIGN KEY (`enterprise_id`) REFERENCES `enterprises` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `document_templates_document_class_id_foreign`
    FOREIGN KEY (`document_class_id`) REFERENCES `document_classes` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `document_template_versions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `document_template_id` BIGINT UNSIGNED NOT NULL,
  `version` INT UNSIGNED NOT NULL DEFAULT 1,
  `content` LONGTEXT NOT NULL,
  `metadata` JSON NULL,
  `is_published` BOOLEAN NOT NULL DEFAULT FALSE,
  `published_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_template_versions_document_template_id_version_unique` (`document_template_id`, `version`),
  CONSTRAINT `document_template_versions_document_template_id_foreign`
    FOREIGN KEY (`document_template_id`) REFERENCES `document_templates` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `collection_account_attachments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `collection_account_id` BIGINT UNSIGNED NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(500) NOT NULL,
  `mime_type` VARCHAR(120) NOT NULL,
  `file_size` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `description` VARCHAR(255) NULL,
  `uploaded_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collection_account_attachments_collection_account_id_foreign` (`collection_account_id`),
  CONSTRAINT `collection_account_attachments_collection_account_id_foreign`
    FOREIGN KEY (`collection_account_id`) REFERENCES `collection_accounts` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `invoice_attachments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` BIGINT UNSIGNED NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(500) NOT NULL,
  `mime_type` VARCHAR(120) NOT NULL,
  `file_size` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `description` VARCHAR(255) NULL,
  `uploaded_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_attachments_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `invoice_attachments_invoice_id_foreign`
    FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

INSERT INTO `document_types` (`id`, `code`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
  (1, 'cc', 'Cedula de ciudadania', 'Colombian citizenship identification card.', TRUE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (2, 'ce', 'Cedula de extranjeria', 'Foreign resident identification card.', TRUE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (3, 'passport', 'Pasaporte', 'Passport document.', TRUE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (4, 'ti', 'Tarjeta de identidad', 'Minor identity card.', TRUE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (5, 'nit', 'NIT', 'Colombian tax identification number.', TRUE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `description` = VALUES(`description`),
  `is_active` = VALUES(`is_active`),
  `updated_at` = CURRENT_TIMESTAMP;

INSERT INTO `document_classes` (`id`, `code`, `name`, `description`, `created_at`, `updated_at`) VALUES
  (1, 'collection_account', 'Collection Account', 'Payment request or account receivable document.', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (2, 'invoice', 'Invoice', 'Commercial invoice document.', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (3, 'quotation', 'Price Quote', 'Commercial quotation document.', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (4, 'proposal', 'Proposal', 'Commercial proposal document.', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `description` = VALUES(`description`),
  `updated_at` = CURRENT_TIMESTAMP;

INSERT INTO `document_statuses` (`id`, `code`, `name`, `description`, `created_at`, `updated_at`) VALUES
  (1, 'draft', 'Draft', 'Document is being edited.', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (2, 'sent', 'Sent', 'Document was sent to the customer.', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (3, 'approved', 'Approved', 'Document was approved.', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (4, 'rejected', 'Rejected', 'Document was rejected.', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (5, 'expired', 'Expired', 'Document validity has expired.', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (6, 'cancelled', 'Cancelled', 'Document was cancelled.', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (7, 'partially_paid', 'Partially Paid', 'Document has partial payments.', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (8, 'paid', 'Paid', 'Document has been fully paid.', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `description` = VALUES(`description`),
  `updated_at` = CURRENT_TIMESTAMP;

INSERT INTO `currencies` (`id`, `code`, `name`, `symbol`, `decimal_place`, `created_at`, `updated_at`) VALUES
  (1, 'COP', 'Colombian Peso', '$', 2, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (2, 'USD', 'US Dollar', 'US$', 2, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `symbol` = VALUES(`symbol`),
  `decimal_place` = VALUES(`decimal_place`),
  `updated_at` = CURRENT_TIMESTAMP;

INSERT INTO `payment_methods` (`id`, `code`, `name`, `requires_bank_account`, `is_active`, `created_at`, `updated_at`) VALUES
  (1, 'cash', 'Cash', FALSE, TRUE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (2, 'check', 'Check', FALSE, TRUE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (3, 'transfer', 'Bank Transfer', TRUE, TRUE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `requires_bank_account` = VALUES(`requires_bank_account`),
  `is_active` = VALUES(`is_active`),
  `updated_at` = CURRENT_TIMESTAMP;

INSERT INTO `enterprises` (`id`, `document_type_id`, `document_number`, `name`, `email`, `is_issuer`, `is_customer`, `is_supplier`, `created_at`, `updated_at`) VALUES
  (1, 5, '900.093.735-8', 'CYMETRIA Group SAS', 'docentes@cymetria.com', TRUE, TRUE, FALSE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (2, 5, '860.012.336-1', 'INSTITUTO COLOMBIANO DE NORMAS TECNICAS Y CERTIFICACION - ICONTEC', 'proveedorsuroccidente@icontec.org', FALSE, TRUE, TRUE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `email` = VALUES(`email`),
  `is_issuer` = VALUES(`is_issuer`),
  `is_customer` = VALUES(`is_customer`),
  `is_supplier` = VALUES(`is_supplier`),
  `updated_at` = CURRENT_TIMESTAMP;

INSERT INTO `people` (`id`, `document_type_id`, `document_number`, `name`, `lastname`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
  (1, 1, '16459137', 'Eliecer', 'Mesias', 'eliecer.mesias@gmail.com', '3175147301', 'Cr 34 # 13 - 51 Apt 201-1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `lastname` = VALUES(`lastname`),
  `email` = VALUES(`email`),
  `phone` = VALUES(`phone`),
  `address` = VALUES(`address`),
  `updated_at` = CURRENT_TIMESTAMP;

INSERT INTO `menus` (`id`, `menu_id`, `name`, `icon`, `url`, `current`, `priority`, `created_at`, `updated_at`) VALUES
  (1, NULL, 'Dashboard', 'home', '/dashboard', NULL, 10, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  (2, NULL, 'Enterprises', 'building-office', '/enterprises', NULL, 20, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `icon` = VALUES(`icon`),
  `url` = VALUES(`url`),
  `current` = VALUES(`current`),
  `priority` = VALUES(`priority`),
  `updated_at` = CURRENT_TIMESTAMP;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
