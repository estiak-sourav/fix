SET FOREIGN_KEY_CHECKS=0;

-- MySQL dump 10.13  Distrib 8.4.11, for macos26.6 (arm64)
--
-- Host: 127.0.0.1    Database: fix_db
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.28-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_activity_logs`
--

DROP TABLE IF EXISTS `admin_activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint(20) unsigned DEFAULT NULL,
  `admin_name` varchar(255) DEFAULT NULL,
  `admin_email` varchar(255) DEFAULT NULL,
  `admin_role` varchar(255) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `action` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` varchar(255) DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_activity_logs_admin_id_foreign` (`admin_id`),
  KEY `admin_activity_logs_created_at_category_index` (`created_at`,`category`),
  KEY `admin_activity_logs_category_index` (`category`),
  KEY `admin_activity_logs_action_index` (`action`),
  KEY `admin_activity_logs_subject_type_index` (`subject_type`),
  KEY `admin_activity_logs_subject_id_index` (`subject_id`),
  CONSTRAINT `admin_activity_logs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admin_notifications`
--

DROP TABLE IF EXISTS `admin_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT 'new_booking',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `booking_id` bigint(20) unsigned DEFAULT NULL,
  `action_url` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `sound_played` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_notifications_booking_id_foreign` (`booking_id`),
  KEY `admin_notifications_type_index` (`type`),
  KEY `admin_notifications_is_read_index` (`is_read`),
  KEY `admin_notifications_sound_played_index` (`sound_played`),
  CONSTRAINT `admin_notifications_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admin_password_reset_tokens`
--

DROP TABLE IF EXISTS `admin_password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admin_payment_methods`
--

DROP TABLE IF EXISTS `admin_payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_payment_methods` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `method_type` varchar(30) NOT NULL,
  `account_number` varchar(60) DEFAULT NULL,
  `account_name` varchar(100) DEFAULT NULL,
  `number_type` varchar(20) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `branch_name` varchar(100) DEFAULT NULL,
  `routing_number` varchar(50) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `role` varchar(30) NOT NULL DEFAULT 'super_admin',
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `email_notification_preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`email_notification_preferences`)),
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `amc_invoices`
--

DROP TABLE IF EXISTS `amc_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `amc_invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `billing_cycle_label` varchar(255) DEFAULT NULL,
  `period_start` date DEFAULT NULL,
  `period_end` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` varchar(30) NOT NULL DEFAULT 'unpaid',
  `paid_at` timestamp NULL DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `collected_by_admin_id` bigint(20) unsigned DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `amc_invoices_invoice_number_unique` (`invoice_number`),
  KEY `amc_invoices_collected_by_admin_id_foreign` (`collected_by_admin_id`),
  KEY `amc_invoices_booking_id_status_index` (`booking_id`,`status`),
  KEY `amc_invoices_due_date_index` (`due_date`),
  CONSTRAINT `amc_invoices_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `amc_invoices_collected_by_admin_id_foreign` FOREIGN KEY (`collected_by_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `amc_notes`
--

DROP TABLE IF EXISTS `amc_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `amc_notes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `admin_id` bigint(20) unsigned DEFAULT NULL,
  `partner_id` bigint(20) unsigned DEFAULT NULL,
  `sender_type` varchar(30) NOT NULL DEFAULT 'customer',
  `sender_name` varchar(150) NOT NULL,
  `note` text NOT NULL,
  `is_customer_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `amc_notes_user_id_foreign` (`user_id`),
  KEY `amc_notes_admin_id_foreign` (`admin_id`),
  KEY `amc_notes_partner_id_foreign` (`partner_id`),
  KEY `amc_notes_booking_id_created_at_index` (`booking_id`,`created_at`),
  CONSTRAINT `amc_notes_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `amc_notes_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `amc_notes_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE SET NULL,
  CONSTRAINT `amc_notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `amc_plans`
--

DROP TABLE IF EXISTS `amc_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `amc_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `badge` varchar(255) DEFAULT NULL,
  `tier` varchar(255) DEFAULT NULL,
  `target_audience` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `price_period` varchar(255) DEFAULT NULL,
  `sla_response_time` varchar(255) NOT NULL DEFAULT '2h Emergency Response',
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `button_text` varchar(255) NOT NULL DEFAULT 'Select Plan',
  `order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `amc_plans_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `amc_shifts`
--

DROP TABLE IF EXISTS `amc_shifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `amc_shifts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `partner_id` bigint(20) unsigned DEFAULT NULL,
  `assigned_partner_crew_id` bigint(20) unsigned DEFAULT NULL,
  `shift_number` int(11) NOT NULL DEFAULT 1,
  `title` varchar(255) NOT NULL,
  `scheduled_date` date NOT NULL,
  `scheduled_time_slot` varchar(100) DEFAULT NULL,
  `arrival_window` varchar(100) DEFAULT NULL,
  `shift_cost` decimal(10,2) DEFAULT NULL,
  `payout_amount` decimal(10,2) DEFAULT NULL,
  `payout_status` varchar(40) NOT NULL DEFAULT 'pending',
  `payment_collected_by` varchar(30) DEFAULT NULL,
  `collected_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_reference` varchar(100) DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'scheduled',
  `cancelled_by` varchar(30) DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `completion_notes` text DEFAULT NULL,
  `crew_notes` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `amc_shifts_assigned_partner_crew_id_foreign` (`assigned_partner_crew_id`),
  KEY `amc_shifts_booking_id_scheduled_date_index` (`booking_id`,`scheduled_date`),
  KEY `amc_shifts_partner_id_status_index` (`partner_id`,`status`),
  CONSTRAINT `amc_shifts_assigned_partner_crew_id_foreign` FOREIGN KEY (`assigned_partner_crew_id`) REFERENCES `partner_crews` (`id`) ON DELETE SET NULL,
  CONSTRAINT `amc_shifts_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `amc_shifts_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `booking_status_logs`
--

DROP TABLE IF EXISTS `booking_status_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_status_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `changed_by_user_id` bigint(20) unsigned DEFAULT NULL,
  `changed_by_admin_id` bigint(20) unsigned DEFAULT NULL,
  `from_status` varchar(30) NOT NULL,
  `to_status` varchar(30) NOT NULL,
  `comment` text DEFAULT NULL,
  `is_customer_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_status_logs_booking_id_foreign` (`booking_id`),
  KEY `booking_status_logs_changed_by_user_id_foreign` (`changed_by_user_id`),
  KEY `booking_status_logs_changed_by_admin_id_foreign` (`changed_by_admin_id`),
  CONSTRAINT `booking_status_logs_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_status_logs_changed_by_admin_id_foreign` FOREIGN KEY (`changed_by_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `booking_status_logs_changed_by_user_id_foreign` FOREIGN KEY (`changed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_number` varchar(50) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `service_id` bigint(20) unsigned DEFAULT NULL,
  `amc_plan_id` bigint(20) unsigned DEFAULT NULL,
  `service_type` varchar(30) NOT NULL DEFAULT 'on_demand',
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(30) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `property_type` varchar(50) NOT NULL DEFAULT 'commercial',
  `estimated_sqft` int(10) unsigned DEFAULT NULL,
  `division` varchar(50) NOT NULL DEFAULT 'Dhaka',
  `district` varchar(50) NOT NULL DEFAULT 'Dhaka',
  `area` varchar(80) NOT NULL,
  `street_address` text NOT NULL,
  `preferred_date` date NOT NULL,
  `preferred_time_slot` varchar(50) NOT NULL,
  `arrival_window` varchar(80) DEFAULT NULL,
  `estimated_arrival_at` timestamp NULL DEFAULT NULL,
  `urgency` varchar(30) NOT NULL DEFAULT 'standard',
  `problem_description` longtext NOT NULL,
  `attached_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attached_images`)),
  `completion_photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`completion_photos`)),
  `status` varchar(30) NOT NULL DEFAULT 'pending_review',
  `partner_job_status` varchar(30) DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `partner_notes` text DEFAULT NULL,
  `partner_payout_amount` decimal(10,2) DEFAULT NULL,
  `partner_payout_status` varchar(20) NOT NULL DEFAULT 'unpaid',
  `partner_payout_paid_at` timestamp NULL DEFAULT NULL,
  `partner_payout_method` varchar(30) DEFAULT NULL,
  `partner_payout_reference` varchar(100) DEFAULT NULL,
  `partner_payout_notes` text DEFAULT NULL,
  `partner_worked_hours` decimal(5,2) DEFAULT NULL,
  `assigned_team` varchar(255) DEFAULT NULL,
  `partner_id` bigint(20) unsigned DEFAULT NULL,
  `assigned_partner_crew_id` bigint(20) unsigned DEFAULT NULL,
  `estimated_cost` decimal(10,2) DEFAULT NULL,
  `final_cost` decimal(10,2) DEFAULT NULL,
  `payment_status` varchar(30) NOT NULL DEFAULT 'pending',
  `payment_collected_by` varchar(30) DEFAULT NULL,
  `payment_method` varchar(40) DEFAULT NULL,
  `collected_amount` decimal(10,2) DEFAULT NULL,
  `payment_reference` varchar(120) DEFAULT NULL,
  `payment_collected_at` timestamp NULL DEFAULT NULL,
  `call_status` varchar(50) DEFAULT NULL,
  `call_notes` text DEFAULT NULL,
  `completion_feedback` text DEFAULT NULL,
  `review_rating` tinyint(3) unsigned DEFAULT NULL,
  `review_comment` text DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `accepted_at` timestamp NULL DEFAULT NULL,
  `partner_assigned_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `contract_start_date` date DEFAULT NULL,
  `contract_end_date` date DEFAULT NULL,
  `contract_tenure` varchar(50) DEFAULT NULL,
  `agreement_document_path` varchar(255) DEFAULT NULL,
  `amc_package_notes` text DEFAULT NULL,
  `contract_status` varchar(30) DEFAULT NULL,
  `cancellation_requested` tinyint(1) NOT NULL DEFAULT 0,
  `cancellation_requested_at` timestamp NULL DEFAULT NULL,
  `cancellation_requested_by` varchar(30) DEFAULT NULL,
  `cancellation_request_reason` text DEFAULT NULL,
  `cancellation_review_status` varchar(30) DEFAULT NULL,
  `cancellation_review_notes` text DEFAULT NULL,
  `cancellation_reviewed_at` timestamp NULL DEFAULT NULL,
  `cancellation_reviewed_by_admin_id` bigint(20) unsigned DEFAULT NULL,
  `crew_cancellation_reason` text DEFAULT NULL,
  `crew_cancellation_requested_at` timestamp NULL DEFAULT NULL,
  `billing_cycle` varchar(50) NOT NULL DEFAULT 'monthly',
  `amc_billing_type` varchar(30) NOT NULL DEFAULT 'monthly',
  `recurring_amount` decimal(12,2) DEFAULT NULL,
  `payout_per_shift` decimal(10,2) DEFAULT NULL,
  `billing_day` tinyint(3) unsigned DEFAULT 1,
  `next_billing_date` date DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_booking_number_unique` (`booking_number`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_service_id_foreign` (`service_id`),
  KEY `bookings_amc_plan_id_foreign` (`amc_plan_id`),
  KEY `bookings_status_index` (`status`),
  KEY `bookings_partner_id_foreign` (`partner_id`),
  KEY `bookings_assigned_partner_crew_id_foreign` (`assigned_partner_crew_id`),
  KEY `bookings_partner_payout_status_index` (`partner_payout_status`),
  CONSTRAINT `bookings_amc_plan_id_foreign` FOREIGN KEY (`amc_plan_id`) REFERENCES `amc_plans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_assigned_partner_crew_id_foreign` FOREIGN KEY (`assigned_partner_crew_id`) REFERENCES `partner_crews` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `daily_analytics`
--

DROP TABLE IF EXISTS `daily_analytics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `daily_analytics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `total_visitors` int(10) unsigned NOT NULL DEFAULT 0,
  `new_visitors` int(10) unsigned NOT NULL DEFAULT 0,
  `service_visitors` int(10) unsigned NOT NULL DEFAULT 0,
  `amc_visitors` int(10) unsigned NOT NULL DEFAULT 0,
  `logins_count` int(10) unsigned NOT NULL DEFAULT 0,
  `registrations_count` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `daily_analytics_date_unique` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `districts`
--

DROP TABLE IF EXISTS `districts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `districts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `division_id` bigint(20) unsigned NOT NULL,
  `name` varchar(60) NOT NULL,
  `bn_name` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `districts_division_id_foreign` (`division_id`),
  KEY `districts_name_index` (`name`),
  KEY `districts_is_active_index` (`is_active`),
  CONSTRAINT `districts_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `divisions`
--

DROP TABLE IF EXISTS `divisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `divisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `bn_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `divisions_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `email_otps`
--

DROP TABLE IF EXISTS `email_otps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_otps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `otp_code_hash` varchar(255) NOT NULL,
  `purpose` varchar(50) NOT NULL DEFAULT 'login',
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `attempts` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `is_consumed` tinyint(1) NOT NULL DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email_otps_email_index` (`email`),
  KEY `email_otps_expires_at_index` (`expires_at`),
  KEY `email_otps_is_consumed_index` (`is_consumed`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(50) NOT NULL DEFAULT 'general',
  `question` text NOT NULL,
  `answer` longtext NOT NULL,
  `order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `faqs_category_index` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `outreach_tasks`
--

DROP TABLE IF EXISTS `outreach_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `outreach_tasks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `admin_id` bigint(20) unsigned DEFAULT NULL,
  `created_by_admin_id` bigint(20) unsigned DEFAULT NULL,
  `completed_by_admin_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `task_type` varchar(50) NOT NULL DEFAULT 'follow_up',
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `priority` varchar(20) NOT NULL DEFAULT 'normal',
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `due_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_automated` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `outreach_tasks_admin_id_foreign` (`admin_id`),
  KEY `outreach_tasks_created_by_admin_id_foreign` (`created_by_admin_id`),
  KEY `outreach_tasks_completed_by_admin_id_foreign` (`completed_by_admin_id`),
  KEY `outreach_tasks_status_due_at_index` (`status`,`due_at`),
  KEY `outreach_tasks_booking_id_status_index` (`booking_id`,`status`),
  KEY `outreach_tasks_user_id_foreign` (`user_id`),
  CONSTRAINT `outreach_tasks_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `outreach_tasks_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  CONSTRAINT `outreach_tasks_completed_by_admin_id_foreign` FOREIGN KEY (`completed_by_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `outreach_tasks_created_by_admin_id_foreign` FOREIGN KEY (`created_by_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `outreach_tasks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `partner_crews`
--

DROP TABLE IF EXISTS `partner_crews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partner_crews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `partner_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `trade_skill` varchar(150) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_crews_partner_id_foreign` (`partner_id`),
  CONSTRAINT `partner_crews_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `partner_leaves`
--

DROP TABLE IF EXISTS `partner_leaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partner_leaves` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `partner_id` bigint(20) unsigned NOT NULL,
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_leaves_partner_id_foreign` (`partner_id`),
  CONSTRAINT `partner_leaves_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `partner_notifications`
--

DROP TABLE IF EXISTS `partner_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partner_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `partner_id` bigint(20) unsigned NOT NULL,
  `booking_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(40) NOT NULL DEFAULT 'general',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `action_url` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_notifications_partner_id_foreign` (`partner_id`),
  KEY `partner_notifications_booking_id_foreign` (`booking_id`),
  KEY `partner_notifications_type_index` (`type`),
  KEY `partner_notifications_is_read_index` (`is_read`),
  CONSTRAINT `partner_notifications_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  CONSTRAINT `partner_notifications_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `partner_payment_methods`
--

DROP TABLE IF EXISTS `partner_payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partner_payment_methods` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `partner_id` bigint(20) unsigned NOT NULL,
  `method_type` varchar(30) NOT NULL,
  `account_number` varchar(60) NOT NULL,
  `account_name` varchar(100) DEFAULT NULL,
  `number_type` varchar(20) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `branch_name` varchar(100) DEFAULT NULL,
  `routing_number` varchar(50) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_payment_methods_partner_id_foreign` (`partner_id`),
  CONSTRAINT `partner_payment_methods_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `partner_payouts`
--

DROP TABLE IF EXISTS `partner_payouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partner_payouts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payout_number` varchar(40) NOT NULL,
  `direction` varchar(30) NOT NULL DEFAULT 'admin_to_partner',
  `initiated_by` varchar(20) NOT NULL DEFAULT 'partner',
  `partner_id` bigint(20) unsigned NOT NULL,
  `partner_payment_method_id` bigint(20) unsigned DEFAULT NULL,
  `admin_payment_method_id` bigint(20) unsigned DEFAULT NULL,
  `payment_method_type` varchar(30) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `payment_method_details` varchar(255) DEFAULT NULL,
  `transaction_reference` varchar(100) DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `partner_notes` text DEFAULT NULL,
  `proof_file` varchar(255) DEFAULT NULL,
  `requested_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `processed_by_admin_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partner_payouts_payout_number_unique` (`payout_number`),
  KEY `partner_payouts_partner_id_foreign` (`partner_id`),
  KEY `partner_payouts_partner_payment_method_id_foreign` (`partner_payment_method_id`),
  KEY `partner_payouts_processed_by_admin_id_foreign` (`processed_by_admin_id`),
  KEY `partner_payouts_status_index` (`status`),
  KEY `partner_payouts_admin_payment_method_id_foreign` (`admin_payment_method_id`),
  KEY `partner_payouts_direction_index` (`direction`),
  KEY `partner_payouts_initiated_by_index` (`initiated_by`),
  CONSTRAINT `partner_payouts_admin_payment_method_id_foreign` FOREIGN KEY (`admin_payment_method_id`) REFERENCES `admin_payment_methods` (`id`) ON DELETE SET NULL,
  CONSTRAINT `partner_payouts_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `partner_payouts_partner_payment_method_id_foreign` FOREIGN KEY (`partner_payment_method_id`) REFERENCES `partner_payment_methods` (`id`) ON DELETE SET NULL,
  CONSTRAINT `partner_payouts_processed_by_admin_id_foreign` FOREIGN KEY (`processed_by_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `company_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `trade_domain` text NOT NULL,
  `technicians_count` varchar(30) NOT NULL DEFAULT '1-5',
  `coverage_areas` text NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `is_currently_offline` tinyint(1) NOT NULL DEFAULT 0,
  `offline_until` timestamp NULL DEFAULT NULL,
  `offline_reason` varchar(255) DEFAULT NULL,
  `partner_type` varchar(30) NOT NULL DEFAULT 'individual',
  `payment_method` varchar(50) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `account_holder` varchar(100) DEFAULT NULL,
  `account_number` varchar(60) DEFAULT NULL,
  `branch_name` varchar(100) DEFAULT NULL,
  `mfs_number` varchar(30) DEFAULT NULL,
  `commission_rate` decimal(5,2) DEFAULT 15.00,
  `earning_model` varchar(30) NOT NULL DEFAULT 'commission',
  `payout_rate` decimal(10,2) DEFAULT NULL,
  `payout_terms` varchar(50) NOT NULL DEFAULT 'bi_weekly',
  `admin_notes` text DEFAULT NULL,
  `notification_preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`notification_preferences`)),
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by_admin_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partners_user_id_foreign` (`user_id`),
  KEY `partners_approved_by_admin_id_foreign` (`approved_by_admin_id`),
  KEY `partners_phone_index` (`phone`),
  KEY `partners_status_index` (`status`),
  CONSTRAINT `partners_approved_by_admin_id_foreign` FOREIGN KEY (`approved_by_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `partners_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sectors`
--

DROP TABLE IF EXISTS `sectors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sectors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `short_desc` text DEFAULT NULL,
  `key_metrics` varchar(255) DEFAULT NULL,
  `bullet_points` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`bullet_points`)),
  `banner_image` varchar(255) DEFAULT NULL,
  `order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sectors_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `service_categories`
--

DROP TABLE IF EXISTS `service_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `hero_image_path` varchar(255) DEFAULT NULL,
  `gallery_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_images`)),
  `price_starting` decimal(10,2) DEFAULT NULL,
  `pricing_unit` varchar(255) DEFAULT NULL,
  `sla_hours` smallint(5) unsigned NOT NULL DEFAULT 4,
  `scope_included` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`scope_included`)),
  `scope_excluded` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`scope_excluded`)),
  `compliance_certs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`compliance_certs`)),
  `is_emergency` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_slug_unique` (`slug`),
  KEY `services_category_id_foreign` (`category_id`),
  CONSTRAINT `services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_contents`
--

DROP TABLE IF EXISTS `site_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_contents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section_key` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `title` text DEFAULT NULL,
  `subtitle` text DEFAULT NULL,
  `badge` varchar(255) DEFAULT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `button_url` varchar(255) DEFAULT NULL,
  `secondary_button_text` varchar(255) DEFAULT NULL,
  `secondary_button_url` varchar(255) DEFAULT NULL,
  `media_path` varchar(255) DEFAULT NULL,
  `media_type` varchar(30) NOT NULL DEFAULT 'image',
  `meta_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_contents_section_key_unique` (`section_key`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` longtext DEFAULT NULL,
  `group` varchar(50) NOT NULL DEFAULT 'general',
  `type` varchar(30) NOT NULL DEFAULT 'text',
  `label` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`),
  KEY `site_settings_group_index` (`group`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `support_ticket_messages`
--

DROP TABLE IF EXISTS `support_ticket_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `support_ticket_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `support_ticket_id` bigint(20) unsigned NOT NULL,
  `sender_type` varchar(255) NOT NULL,
  `sender_id` bigint(20) unsigned DEFAULT NULL,
  `sender_name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `support_ticket_messages_support_ticket_id_foreign` (`support_ticket_id`),
  CONSTRAINT `support_ticket_messages_support_ticket_id_foreign` FOREIGN KEY (`support_ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `support_tickets`
--

DROP TABLE IF EXISTS `support_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `support_tickets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_number` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `admin_id` bigint(20) unsigned DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `priority` varchar(255) NOT NULL DEFAULT 'normal',
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `created_by` varchar(255) NOT NULL DEFAULT 'customer',
  `access_token` varchar(64) NOT NULL,
  `last_reply_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `support_tickets_ticket_number_unique` (`ticket_number`),
  UNIQUE KEY `support_tickets_access_token_unique` (`access_token`),
  KEY `support_tickets_user_id_foreign` (`user_id`),
  KEY `support_tickets_admin_id_foreign` (`admin_id`),
  KEY `support_tickets_customer_email_index` (`customer_email`),
  CONSTRAINT `support_tickets_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `support_tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimonials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_name` varchar(255) NOT NULL,
  `client_title` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `rating` tinyint(3) unsigned NOT NULL DEFAULT 5,
  `quote` text NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `thanas`
--

DROP TABLE IF EXISTS `thanas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `thanas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `district_id` bigint(20) unsigned NOT NULL,
  `name` varchar(80) NOT NULL,
  `bn_name` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `thanas_district_id_foreign` (`district_id`),
  KEY `thanas_name_index` (`name`),
  KEY `thanas_is_active_index` (`is_active`),
  CONSTRAINT `thanas_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=544 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `property_type` varchar(50) DEFAULT NULL,
  `default_address` text DEFAULT NULL,
  `division` varchar(50) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `area` varchar(80) DEFAULT NULL,
  `role` varchar(30) NOT NULL DEFAULT 'customer',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-15 23:51:17


-- SEED DATA
LOCK TABLES `divisions` WRITE;
INSERT INTO `divisions` VALUES (1,'Dhaka','ঢাকা','2026-10-24 02:32:18','2026-10-24 02:32:18'),(2,'Chattogram','চট্টগ্রাম','2026-10-24 02:32:18','2026-10-24 02:32:18'),(3,'Sylhet','সিলেট','2026-10-24 02:32:18','2026-10-24 02:32:18'),(4,'Rajshahi','রাজশাহী','2026-10-24 02:32:18','2026-10-24 02:32:18'),(5,'Khulna','খুলনা','2026-10-24 02:32:18','2026-10-24 02:32:18'),(6,'Barishal','বরিশাল','2026-10-24 02:32:18','2026-10-24 02:32:18'),(7,'Rangpur','রংপুর','2026-10-24 02:32:18','2026-10-24 02:32:18'),(8,'Mymensingh','ময়মনসিংহ','2026-10-24 02:32:18','2026-10-24 02:32:18');
UNLOCK TABLES;
LOCK TABLES `districts` WRITE;
INSERT INTO `districts` VALUES (1,1,'Dhaka','ঢাকা',1,'2026-10-24 02:32:18','2026-11-09 02:32:38'),(2,1,'Gazipur','গাজীপুর',1,'2026-10-24 02:32:18','2026-11-09 02:32:42'),(3,1,'Narayanganj','নারায়ণগঞ্জ',0,'2026-10-24 02:32:18','2026-11-09 02:28:02'),(4,1,'Tangail','টাঙ্গাইল',0,'2026-10-24 02:32:18','2026-11-09 02:28:14'),(5,1,'Faridpur','ফরিদপুর',0,'2026-10-24 02:32:18','2026-11-09 02:27:38'),(6,1,'Gopalganj','গোপালগঞ্জ',0,'2026-10-24 02:32:18','2026-11-09 02:27:48'),(7,1,'Kishoreganj','কিশোরগঞ্জ',0,'2026-10-24 02:32:18','2026-11-09 02:27:51'),(8,1,'Madaripur','মাদারীপুর',0,'2026-10-24 02:32:18','2026-11-09 02:27:59'),(9,1,'Manikganj','মানিকগঞ্জ',0,'2026-10-24 02:32:18','2026-11-09 02:28:08'),(10,1,'Munshiganj','মুন্সীগঞ্জ',0,'2026-10-24 02:32:18','2026-11-09 02:28:04'),(11,1,'Narsingdi','নরসিংদী',0,'2026-10-24 02:32:18','2026-11-09 02:28:11'),(12,1,'Rajbari','রাজবাড়ী',0,'2026-10-24 02:32:18','2026-11-09 02:28:17'),(13,1,'Shariatpur','শরীয়তপুর',0,'2026-10-24 02:32:18','2026-11-09 02:28:20'),(14,2,'Chattogram','চট্টগ্রাম',0,'2026-10-24 02:32:18','2026-11-09 02:29:09'),(15,2,'Cox\'s Bazar','কক্সবাজার',0,'2026-10-24 02:32:18','2026-11-09 02:28:58'),(16,2,'Cumilla','কুমিল্লা',0,'2026-10-24 02:32:18','2026-11-09 02:29:04'),(17,2,'Feni','ফেনী',0,'2026-10-24 02:32:18','2026-11-09 02:29:20'),(18,2,'Brahmanbaria','ব্রাহ্মণবাড়িয়া',0,'2026-10-24 02:32:18','2026-11-09 02:28:50'),(19,2,'Chandpur','চাঁদপুর',0,'2026-10-24 02:32:18','2026-11-09 02:28:53'),(20,2,'Noakhali','নোয়াখালী',0,'2026-10-24 02:32:18','2026-11-09 02:31:25'),(21,2,'Lakshmipur','লক্ষ্মীপুর',0,'2026-10-24 02:32:18','2026-11-09 02:31:18'),(22,2,'Rangamati','রাঙ্গামাটি',0,'2026-10-24 02:32:18','2026-11-09 02:31:28'),(23,2,'Bandarban','বান্দরবান',0,'2026-10-24 02:32:18','2026-11-09 02:28:39'),(24,2,'Khagrachhari','খাগড়াছড়ি',0,'2026-10-24 02:32:18','2026-11-09 02:30:58'),(25,3,'Sylhet','সিলেট',0,'2026-10-24 02:32:18','2026-11-09 02:32:31'),(26,3,'Moulvibazar','মৌলভীবাজার',0,'2026-10-24 02:32:18','2026-11-09 02:32:28'),(27,3,'Habiganj','হবিগঞ্জ',0,'2026-10-24 02:32:18','2026-11-09 02:29:22'),(28,3,'Sunamganj','সুনামগঞ্জ',0,'2026-10-24 02:32:18','2026-11-09 02:32:30'),(29,4,'Rajshahi','রাজশাহী',0,'2026-10-24 02:32:18','2026-11-09 02:31:40'),(30,4,'Bogura','বগুড়া',0,'2026-10-24 02:32:18','2026-11-09 02:28:43'),(31,4,'Pabna','পাবনা',0,'2026-10-24 02:32:18','2026-11-09 02:31:12'),(32,4,'Sirajganj','সিরাজগঞ্জ',0,'2026-10-24 02:32:18','2026-11-09 02:31:38'),(33,4,'Naogaon','নওগাঁ',0,'2026-10-24 02:32:18','2026-11-09 02:31:34'),(34,4,'Natore','নাটোর',0,'2026-10-24 02:32:18','2026-11-09 02:31:35'),(35,4,'Chapai Nawabganj','চাঁপাইনবাবগঞ্জ',0,'2026-10-24 02:32:18','2026-11-09 02:28:55'),(36,4,'Joypurhat','জয়পুরহাট',0,'2026-10-24 02:32:18','2026-11-09 02:30:55'),(37,5,'Khulna','খুলনা',0,'2026-10-24 02:32:18','2026-11-09 02:31:04'),(38,5,'Jashore','যশোর',0,'2026-10-24 02:32:18','2026-11-09 02:29:29'),(39,5,'Kushtia','কুষ্টিয়া',0,'2026-10-24 02:32:18','2026-11-09 02:31:07'),(40,5,'Bagerhat','বাগেরহাট',0,'2026-10-24 02:32:18','2026-11-09 02:28:36'),(41,5,'Satkhira','সাতক্ষীরা',0,'2026-10-24 02:32:18','2026-11-09 02:31:46'),(42,5,'Chuadanga','চুয়াডাঙ্গা',0,'2026-10-24 02:32:18','2026-11-09 02:29:01'),(43,5,'Jhenaidah','ঝিনাইদহ',0,'2026-10-24 02:32:18','2026-11-09 02:30:50'),(44,5,'Magura','মাগুরা',0,'2026-10-24 02:32:18','2026-11-09 02:30:53'),(45,5,'Meherpur','মেহেরপুর',0,'2026-10-24 02:32:18','2026-11-09 02:31:49'),(46,5,'Narail','নড়াইল',0,'2026-10-24 02:32:18','2026-11-09 02:31:50'),(47,6,'Barishal','বরিশাল',0,'2026-10-24 02:32:18','2026-11-09 02:28:48'),(48,6,'Bhola','ভোলা',0,'2026-10-24 02:32:18','2026-11-09 02:28:45'),(49,6,'Patuakhali','পটুয়াখালী',0,'2026-10-24 02:32:18','2026-11-09 02:31:58'),(50,6,'Pirojpur','পিরোজপুর',0,'2026-10-24 02:32:18','2026-11-09 02:32:00'),(51,6,'Barguna','বরগুনা',0,'2026-10-24 02:32:18','2026-11-09 02:28:40'),(52,6,'Jhalokati','ঝালকাঠি',0,'2026-10-24 02:32:18','2026-11-09 02:29:25'),(53,7,'Rangpur','রংপুর',0,'2026-10-24 02:32:18','2026-11-09 02:32:14'),(54,7,'Dinajpur','দিনাজপুর',0,'2026-10-24 02:32:18','2026-11-09 02:29:14'),(55,7,'Gaibandha','গাইবান্ধা',0,'2026-10-24 02:32:18','2026-11-09 02:29:17'),(56,7,'Kurigram','কুড়িগ্রাম',0,'2026-10-24 02:32:18','2026-11-09 02:31:01'),(57,7,'Lalmonirhat','লালমনিরহাট',0,'2026-10-24 02:32:18','2026-11-09 02:32:07'),(58,7,'Nilphamari','নীলফামারী',0,'2026-10-24 02:32:18','2026-11-09 02:32:08'),(59,7,'Panchagarh','পঞ্চগড়',0,'2026-10-24 02:32:18','2026-11-09 02:32:10'),(60,7,'Thakurgaon','ঠাকুরগাঁও',0,'2026-10-24 02:32:18','2026-11-09 02:32:12'),(61,8,'Mymensingh','ময়মনসিংহ',0,'2026-10-24 02:32:18','2026-11-09 02:31:15'),(62,8,'Jamalpur','জামালপুর',0,'2026-10-24 02:32:18','2026-11-09 02:29:58'),(63,8,'Netrokona','নেত্রকোণা',0,'2026-10-24 02:32:18','2026-11-09 02:31:10'),(64,8,'Sherpur','শেরপুর',0,'2026-10-24 02:32:18','2026-11-09 02:32:20');
UNLOCK TABLES;
LOCK TABLES `thanas` WRITE;
INSERT INTO `thanas` VALUES (1,1,'Dhanmondi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(2,1,'Gulshan',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(3,1,'Banani',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(4,1,'Uttara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(5,1,'Mirpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(6,1,'Mohammadpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(7,1,'Motijheel',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(8,1,'Tejgaon',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(9,1,'Badda',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(10,1,'Khilgaon',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(11,1,'Rampura',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(12,1,'Paltan',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(13,1,'Shahbagh',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(14,1,'New Market',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(15,1,'Lalbagh',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(16,1,'Kotwali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(17,1,'Sutrapur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(18,1,'Demra',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(19,1,'Jatrabari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(20,1,'Keraniganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(21,1,'Savar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(22,1,'Dhamrai',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(23,1,'Nawabganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(24,1,'Dohar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(25,2,'Gazipur Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(26,2,'Tongi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(27,2,'Kaliakair',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(28,2,'Kapasia',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(29,2,'Sreepur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(30,2,'Kaliganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(31,3,'Narayanganj Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(32,3,'Bandar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(33,3,'Fatullah',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(34,3,'Siddhirganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(35,3,'Araihazar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(36,3,'Sonargaon',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(37,3,'Rupganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(38,4,'Tangail Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(39,4,'Mirzapur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(40,4,'Ghatail',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(41,4,'Madhupur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(42,4,'Sakhipur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(43,4,'Kalihati',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(44,4,'Basail',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(45,4,'Gopalpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(46,4,'Delduar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(47,4,'Bhuapur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(48,4,'Nagarpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(49,4,'Dhanbari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(50,5,'Faridpur Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(51,5,'Boalmari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(52,5,'Alfadanga',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(53,5,'Madhukhali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(54,5,'Bhanga',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(55,5,'Nagarkanda',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(56,5,'Charbhadrasan',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(57,5,'Sadarpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(58,5,'Saltha',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(59,6,'Gopalganj Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(60,6,'Kashiani',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(61,6,'Kotalipara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(62,6,'Muksudpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(63,6,'Tungipara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(64,7,'Kishoreganj Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(65,7,'Bhairab',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(66,7,'Bajitpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(67,7,'Kuliarchar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(68,7,'Pakundia',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(69,7,'Katiadi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(70,7,'Karimganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(71,7,'Tarail',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(72,7,'Hossainpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(73,7,'Itna',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(74,7,'Mithamain',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(75,7,'Nikli',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(76,7,'Ashtagram',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(77,8,'Madaripur Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(78,8,'Shibchar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(79,8,'Kalkini',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(80,8,'Rajoir',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(81,8,'Dasar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(82,9,'Manikganj Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(83,9,'Singair',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(84,9,'Saturia',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(85,9,'Ghior',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(86,9,'Shivalaya',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(87,9,'Harirampur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(88,9,'Daulatpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(89,10,'Munshiganj Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(90,10,'Sreenagar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(91,10,'Sirajdikhan',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(92,10,'Louhajang',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(93,10,'Tongibari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(94,10,'Gazaria',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(95,11,'Narsingdi Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(96,11,'Palash',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(97,11,'Shibpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(98,11,'Raipura',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(99,11,'Belabo',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(100,11,'Monohardi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(101,12,'Rajbari Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(102,12,'Goalanda',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(103,12,'Pangsha',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(104,12,'Baliakandi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(105,12,'Kalukhali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(106,13,'Shariatpur Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(107,13,'Zajira',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(108,13,'Naria',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(109,13,'Bhedarganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(110,13,'Damudya',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(111,13,'Gosairhat',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(112,14,'Kotwali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(113,14,'Panchlaish',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(114,14,'Pahartali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(115,14,'Halishahar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(116,14,'Agrabad',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(117,14,'Khulshi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(118,14,'Chandgaon',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(119,14,'Bakalia',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(120,14,'Bayazid',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(121,14,'Patenga',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(122,14,'Double Mooring',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(123,14,'Hathazari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(124,14,'Sitakunda',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(125,14,'Mirsharai',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(126,14,'Patiya',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(127,14,'Raozan',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(128,14,'Rangunia',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(129,14,'Boalkhali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(130,14,'Anwara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(131,14,'Chandanaish',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(132,14,'Satkania',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(133,14,'Lohagara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(134,14,'Banshkhali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(135,14,'Fatikchhari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(136,14,'Karnaphuli',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(137,14,'Sandwip',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(138,15,'Cox\'s Bazar Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(139,15,'Ramu',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(140,15,'Chakaria',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(141,15,'Pekua',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(142,15,'Maheshkhali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(143,15,'Kutubdia',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(144,15,'Ukhiya',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(145,15,'Teknaf',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(146,16,'Cumilla Adarsha Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(147,16,'Cumilla Sadar Dakshin',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(148,16,'Barura',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(149,16,'Brahmanpara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(150,16,'Burichang',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(151,16,'Chandina',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(152,16,'Chauddagram',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(153,16,'Daudkandi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(154,16,'Debidwar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(155,16,'Homna',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(156,16,'Laksam',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(157,16,'Monohorgonj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(158,16,'Meghna',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(159,16,'Muradnagar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(160,16,'Nangalkot',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(161,16,'Titas',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(162,16,'Lalmai',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(163,17,'Feni Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(164,17,'Chhagalnaiya',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(165,17,'Daganbhuiyan',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(166,17,'Parshuram',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(167,17,'Fulgazi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(168,17,'Sonagazi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(169,18,'Brahmanbaria Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(170,18,'Kasba',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(171,18,'Nasirnagar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(172,18,'Nabinagar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(173,18,'Bancharampur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(174,18,'Sarail',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(175,18,'Ashuganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(176,18,'Akhaura',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(177,18,'Bijoynagar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(178,19,'Chandpur Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(179,19,'Faridganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(180,19,'Haimchar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(181,19,'Haziganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(182,19,'Kachua',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(183,19,'Matlab Dakshin',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(184,19,'Matlab Uttar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(185,19,'Shahrasti',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(186,20,'Noakhali Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(187,20,'Begumganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(188,20,'Chatkhil',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(189,20,'Companiganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(190,20,'Hatiya',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(191,20,'Senbagh',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(192,20,'Sonaimuri',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(193,20,'Subarnachar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(194,20,'Kabirhat',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(195,21,'Lakshmipur Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(196,21,'Raipur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(197,21,'Ramganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(198,21,'Ramgati',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(199,21,'Kamalnagar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(200,22,'Rangamati Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(201,22,'Kaptai',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(202,22,'Bagaichhari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(203,22,'Barkal',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(204,22,'Langadu',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(205,22,'Rajasthali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(206,22,'Belaichhari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(207,22,'Juraichhari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(208,22,'Naniarchar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(209,23,'Bandarban Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(210,23,'Ruma',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(211,23,'Thanchi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(212,23,'Rowangchhari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(213,23,'Lama',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(214,23,'Alikadam',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(215,23,'Naikhongchhari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(216,24,'Khagrachhari Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(217,24,'Dighinala',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(218,24,'Panchhari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(219,24,'Mahalchhari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(220,24,'Matiranga',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(221,24,'Manikchhari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(222,24,'Ramgarh',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(223,24,'Lakshmichhari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(224,24,'Guimara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(225,25,'Sylhet Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(226,25,'Kotwali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(227,25,'South Surma',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(228,25,'Beanibazar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(229,25,'Golapganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(230,25,'Balaganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(231,25,'Fenchuganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(232,25,'Bishwanath',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(233,25,'Zakiganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(234,25,'Kanaighat',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(235,25,'Companiganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(236,25,'Gowainghat',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(237,25,'Jaintiapur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(238,25,'Osmani Nagar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(239,26,'Moulvibazar Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(240,26,'Sreemangal',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(241,26,'Kamalganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(242,26,'Kulaura',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(243,26,'Rajnagar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(244,26,'Barlekha',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(245,26,'Juri',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(246,27,'Habiganj Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(247,27,'Nabiganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(248,27,'Madhabpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(249,27,'Chunarughat',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(250,27,'Bahubal',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(251,27,'Baniachong',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(252,27,'Lakhai',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(253,27,'Ajmiriganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(254,27,'Sayestaganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(255,28,'Sunamganj Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(256,28,'Chhatak',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(257,28,'Jagannathpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(258,28,'Derai',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(259,28,'Dharampasha',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(260,28,'Tahirpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(261,28,'Jamalganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(262,28,'Bishwamvarpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(263,28,'Shantiganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(264,28,'Dowarabazar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(265,28,'Shalla',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(266,28,'Madhyanagar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(267,29,'Boalia',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(268,29,'Motihar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(269,29,'Rajpara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(270,29,'Shah Makhdum',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(271,29,'Paba',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(272,29,'Godagari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(273,29,'Tanore',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(274,29,'Mohanpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(275,29,'Bagmara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(276,29,'Durgapur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(277,29,'Puthia',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(278,29,'Charghat',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(279,29,'Bagha',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(280,30,'Bogura Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(281,30,'Shajahanpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(282,30,'Sherpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(283,30,'Shibganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(284,30,'Kahaloo',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(285,30,'Nandigram',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(286,30,'Gabtali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(287,30,'Sariakandi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(288,30,'Sonatala',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(289,30,'Dhunat',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(290,30,'Adamdighi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(291,30,'Dupchanchia',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(292,31,'Pabna Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(293,31,'Ishwardi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(294,31,'Sujanagar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(295,31,'Santhia',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(296,31,'Bera',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(297,31,'Chatmohar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(298,31,'Bhangura',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(299,31,'Faridpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(300,31,'Atgharia',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(301,32,'Sirajganj Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(302,32,'Shahjadpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(303,32,'Ullapara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(304,32,'Belkuchi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(305,32,'Kazipur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(306,32,'Kamarkhanda',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(307,32,'Raiganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(308,32,'Tarash',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(309,32,'Chauhali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(310,33,'Naogaon Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(311,33,'Manda',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(312,33,'Mohadevpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(313,33,'Patnitala',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(314,33,'Dhamoirhat',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(315,33,'Niamatpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(316,33,'Raninagar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(317,33,'Atrai',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(318,33,'Badalgachhi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(319,33,'Porsha',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(320,33,'Sapahar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(321,34,'Natore Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(322,34,'Singra',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(323,34,'Baraigram',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(324,34,'Bagatipara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(325,34,'Gurudaspur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(326,34,'Lalpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(327,34,'Naldanga',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(328,35,'Chapai Nawabganj Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(329,35,'Shibganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(330,35,'Gomastapur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(331,35,'Nachole',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(332,35,'Bholahat',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(333,36,'Joypurhat Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(334,36,'Panchbibi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(335,36,'Kalai',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(336,36,'Khetlal',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(337,36,'Akkelpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(338,37,'Khulna Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(339,37,'Sonadanga',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(340,37,'Khalishpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(341,37,'Daulatpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(342,37,'Khan Jahan Ali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(343,37,'Rupsha',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(344,37,'Dighalia',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(345,37,'Dumuria',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(346,37,'Batiaghata',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(347,37,'Terokhada',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(348,37,'Phultala',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(349,37,'Paikgachha',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(350,37,'Koyra',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(351,37,'Dacope',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(352,38,'Jashore Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(353,38,'Jhikargachha',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(354,38,'Manirampur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(355,38,'Abhaynagar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(356,38,'Keshabpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(357,38,'Bagherpara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(358,38,'Chaugachha',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(359,38,'Sharsha',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(360,39,'Kushtia Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(361,39,'Kumarkhali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(362,39,'Khoksa',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(363,39,'Mirpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(364,39,'Bheramara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(365,39,'Daulatpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(366,40,'Bagerhat Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(367,40,'Mongla',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(368,40,'Fakirhat',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(369,40,'Rampal',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(370,40,'Morrelganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(371,40,'Kachua',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(372,40,'Mollahat',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(373,40,'Chitalmari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(374,40,'Sarankhola',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(375,41,'Satkhira Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(376,41,'Kalaroa',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(377,41,'Tala',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(378,41,'Kaliganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(379,41,'Shyamnagar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(380,41,'Debhata',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(381,41,'Assasuni',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(382,42,'Chuadanga Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(383,42,'Alamdanga',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(384,42,'Damurhuda',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(385,42,'Jibannagar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(386,43,'Jhenaidah Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(387,43,'Kaliganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(388,43,'Kotchandpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(389,43,'Maheshpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(390,43,'Shailkupa',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(391,43,'Harinakunda',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(392,44,'Magura Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(393,44,'Sreepur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(394,44,'Mohammadpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(395,44,'Shalikha',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(396,45,'Meherpur Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(397,45,'Gangni',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(398,45,'Mujibnagar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(399,46,'Narail Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(400,46,'Lohagara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(401,46,'Kalia',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(402,47,'Kotwali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(403,47,'Airport',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(404,47,'Kawnia',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(405,47,'Bakerganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(406,47,'Babuganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(407,47,'Wazirpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(408,47,'Banaripara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(409,47,'Gournadi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(410,47,'Agailjhara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(411,47,'Muladi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(412,47,'Hizla',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(413,47,'Mehendiganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(414,48,'Bhola Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(415,48,'Daulatkhan',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(416,48,'Borhanuddin',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(417,48,'Lalmohan',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(418,48,'Char Fasson',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(419,48,'Tazumuddin',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(420,48,'Monpura',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(421,49,'Patuakhali Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(422,49,'Dumki',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(423,49,'Bauphal',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(424,49,'Galachipa',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(425,49,'Dashmina',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(426,49,'Kalapara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(427,49,'Mirzaganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(428,49,'Rangabali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(429,50,'Pirojpur Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(430,50,'Nesarabad (Swarupkati)',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(431,50,'Bhandaria',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(432,50,'Mathbaria',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(433,50,'Kawkhali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(434,50,'Nazirpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(435,50,'Zianagar (Indurkani)',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(436,51,'Barguna Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(437,51,'Amtali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(438,51,'Patharghata',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(439,51,'Betagi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(440,51,'Bamna',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(441,51,'Taltali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(442,52,'Jhalokati Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(443,52,'Nalchity',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(444,52,'Rajapur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(445,52,'Kathalia',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(446,53,'Kotwali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(447,53,'Tajhat',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(448,53,'Mahiganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(449,53,'Haragach',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(450,53,'Parshuram',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(451,53,'Hazirhat',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(452,53,'Badarganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(453,53,'Gangachhara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(454,53,'Kaunia',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(455,53,'Mithapukur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(456,53,'Pirgachha',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(457,53,'Pirganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(458,53,'Taraganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(459,54,'Dinajpur Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(460,54,'Birganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(461,54,'Kaharole',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(462,54,'Bochaganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(463,54,'Birol',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(464,54,'Parbatipur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(465,54,'Phulbari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(466,54,'Nawabganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(467,54,'Ghoraghat',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(468,54,'Hakimpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(469,54,'Chirirbandar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(470,54,'Khansama',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(471,54,'Biral',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(472,55,'Gaibandha Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(473,55,'Sadullapur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(474,55,'Palashbari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(475,55,'Gobindaganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(476,55,'Sundarganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(477,55,'Saghata',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(478,55,'Phulchhari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(479,56,'Kurigram Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(480,56,'Nageshwari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(481,56,'Bhurungamari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(482,56,'Phulbari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(483,56,'Rajarhat',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(484,56,'Ulipur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(485,56,'Chilmari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(486,56,'Rowmari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(487,56,'Char Rajibpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(488,57,'Lalmonirhat Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(489,57,'Aditmari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(490,57,'Kaliganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(491,57,'Hatibandha',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(492,57,'Patgram',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(493,58,'Nilphamari Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(494,58,'Saidpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(495,58,'Jaldhaka',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(496,58,'Kishoreganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(497,58,'Domar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(498,58,'Dimla',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(499,59,'Panchagarh Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(500,59,'Boda',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(501,59,'Debiganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(502,59,'Atwari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(503,59,'Tetulia',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(504,60,'Thakurgaon Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(505,60,'Pirganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(506,60,'Ranisankail',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(507,60,'Baliadangi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(508,60,'Haripur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(509,61,'Kotwali',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(510,61,'Muktagachha',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(511,61,'Fulbaria',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(512,61,'Trishal',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(513,61,'Bhaluka',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(514,61,'Gaffargaon',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(515,61,'Nandail',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(516,61,'Ishwarganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(517,61,'Haluaghat',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(518,61,'Dhobaura',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(519,61,'Gouripur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(520,61,'Phulpur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(521,61,'Tara Khanda',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(522,62,'Jamalpur Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(523,62,'Sarishabari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(524,62,'Melandaha',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(525,62,'Islampur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(526,62,'Dewanganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(527,62,'Madarganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(528,62,'Bakshiganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(529,63,'Netrokona Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(530,63,'Kendua',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(531,63,'Atpara',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(532,63,'Barhatta',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(533,63,'Mohanganj',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(534,63,'Madan',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(535,63,'Khaliajuri',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(536,63,'Durgapur',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(537,63,'Kalmakanda',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(538,63,'Purbadhala',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(539,64,'Sherpur Sadar',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(540,64,'Nalitabari',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(541,64,'Nakla',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(542,64,'Sreebardi',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18'),(543,64,'Jhenaigati',NULL,1,'2026-10-24 02:32:18','2026-10-24 02:32:18');
UNLOCK TABLES;
LOCK TABLES `service_categories` WRITE;
INSERT INTO `service_categories` VALUES (1,'Facility & Technical Maintenance','maintenance','Electrical, HVAC, Plumbing, Generator, and building plant engineering.','tool',1,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(2,'Cleaning & Specialized Sanitization','cleaning','Corporate janitorial, deep carpet scrubbing, and clinical disinfection.','sparkles',2,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(3,'Security & Fire Safety Systems','security','BNBC/NFPA fire hydrants, smoke detectors, CCTV IP surveillance, and biometrics.','shield',3,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(4,'Interior Works & Renovation','renovation','Office fit-outs, drywall partitions, acoustic dampening, epoxy, and premium paint.','layout',4,1,'2026-10-12 06:23:50','2026-10-12 06:23:50');
UNLOCK TABLES;
LOCK TABLES `services` WRITE;
INSERT INTO `services` VALUES (1,1,'Mechanical & Electrical','electrical','Facility Maintenance & Power Distribution','Complete electrical maintenance, power distribution, wiring, circuit breakers, and lighting solutions for commercial and residential facilities.',NULL,'uploads/services/service_1794305544_mechanical-electrical.png',NULL,'[\"uploads\\/services\\/gallery_1792840410_De0fz0.png\",\"uploads\\/services\\/gallery_1792840432_ACGxky.png\"]',1500.00,'Starting Inspection',1,'[\"Switchgear & Distribution Panels\",\"Wiring & Lighting Installation\",\"Emergency Power Troubleshooting\",\"Thermal Scanning & Safety Audit\",\"Thermal Scanning & Safety Audit 2\"]','[\"Major power grid infrastructure outside boundary\"]','[\"DESCO\\/DPDC Certified Techs\",\"Thermal Imaging Audit\"]',1,1,1,1,'2026-10-12 06:23:50','2026-11-14 06:19:00'),(2,1,'Plumbing & Sanitary Solutions','plumbing','Precision Leak Detection & Booster Pumps','Precision leak detection, high-pressure piping, bathroom fittings, submersible pump servicing, and underground reservoir cleaning.',NULL,'https://images.unsplash.com/photo-1585704032915-c3400ca199e7?auto=format&fit=crop&w=800&q=80',NULL,NULL,1200.00,'Starting Inspection',1,'[\"Acoustic Leak Detection\",\"Water Pump & Booster Systems\",\"Sanitary & Bathroom Overhaul\",\"Reservoir Deep Disinfection\"]',NULL,'[\"WASA Approved Plumbers\",\"Ultrasonic Detection\"]',1,1,2,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(3,1,'HVAC & Air Conditioning','hvac','Chillers, VRF & Split AC Engineering','Chiller maintenance, VRF/Central HVAC engineering, split & cassette AC servicing, air duct sanitation, and eco-refrigerant replenishment.',NULL,'https://images.unsplash.com/photo-1621905252507-b35492cc74b4?auto=format&fit=crop&w=800&q=80',NULL,NULL,1800.00,'Per Unit Jet Wash',2,'[\"Commercial Chillers & VRF\",\"Split & Cassette AC Servicing\",\"Air Duct Deep Cleaning\",\"Refrigerant Leak Diagnostics\"]',NULL,'[\"ASHRAE Standards\",\"Certified Refrigerant Techs\"]',1,1,3,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(4,2,'Commercial & Office Cleaning','cleaning','Daily Janitorial & Deep Floor Scrubbing','Daily office janitorial programs, industrial floor scrubbing, carpet shampooing, glass facade washing, and post-construction scrubbing.',NULL,'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=800&q=80',NULL,NULL,2500.00,'Custom Scope',4,'[\"Daily Corporate Janitorial Staff\",\"Deep Carpet & Chair Shampooing\",\"High-Pressure Floor Scrubbing\",\"Exterior Glass Facade Cleaning\"]',NULL,'[\"Green Chemical Certified\",\"Uniformed & Vetted Staff\"]',0,1,4,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(5,2,'Clinical & Specialized Sanitization','clinical','ICU, Cleanrooms & Bio-Safety Decontamination','Hospital-grade disinfection, cleanrooms, medical laboratories, and pharmaceutical facilities following stringent biosafety standards.',NULL,'https://images.unsplash.com/photo-1584744982491-665216d95f8b?auto=format&fit=crop&w=800&q=80',NULL,NULL,5000.00,'Facility Scope',2,'[\"ICU & Operating Theater Sanitation\",\"Pharma Cleanroom Decontamination\",\"Pathogen Barrier Fogging\",\"Bio-Waste Handling Protocols\"]',NULL,'[\"DGHS Hygiene Criteria\",\"WHO Biosafety Level 2\"]',1,1,5,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(6,3,'Fire Safety & Alarm Systems','fire_safety','NFPA & BNBC Compliance, Hydrants & Extinguishers','Certified inspection, refilling, sprinkler network testing, addressable smoke alarm troubleshooting, and regulatory safety compliance.',NULL,'https://images.unsplash.com/photo-1599839575945-a9e5af0c3fa5?auto=format&fit=crop&w=800&q=80',NULL,NULL,3500.00,'Audit & Certification',2,'[\"Extinguisher Hydro-Testing & Refill\",\"Fire Hydrant & Pump Testing\",\"Smoke & Heat Detector Audits\",\"Evacuation Drills & Compliance\"]',NULL,'[\"FSCD Bangladesh Standards\",\"NFPA 10\\/13\\/72\"]',1,1,6,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(7,3,'CCTV Installation & Surveillance','cctv','Smart IP Surveillance & Access Control','High-definition IP surveillance, NVR/DVR cloud integration, biometric access control, perimeter intrusion barriers, and smart monitoring.',NULL,'https://images.unsplash.com/photo-1557597774-9d273605dfa9?auto=format&fit=crop&w=800&q=80',NULL,NULL,2000.00,'Per Channel Point',2,'[\"AI-Powered Smart IP Cameras\",\"Biometric Access & Turnstiles\",\"Perimeter Intrusion Sensors\",\"Central Control Room Integration\"]',NULL,'[\"Hikvision\\/Dahua Certified\",\"Cisco Fiber Routing\"]',0,1,7,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(8,1,'Generator & Backup Power (IPS)','generator','Diesel Overhauls, ATS & Battery Banks','Diesel generator routine overhauls, Automatic Transfer Switch (ATS) repairs, industrial UPS diagnostics, and battery health management.',NULL,'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80',NULL,NULL,4500.00,'Starting Routine Overhaul',1,'[\"Diesel Generator Servicing & Filters\",\"ATS (Auto Transfer Switch) Testing\",\"Industrial UPS & Battery Banks\",\"Fuel Quality & Exhaust Audits\"]',NULL,'[\"Perkins\\/Cummins Certified\",\"Load Bank Testing\"]',1,1,8,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(9,4,'Interior Works & Renovation','renovation','Partitions, False Ceilings, Epoxy & Paint','Turnkey architectural renovation, gypsum false ceilings, drywall partitions, acoustic dampening, epoxy flooring, and premium painting.',NULL,'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=800&q=80',NULL,NULL,10000.00,'Milestone Estimate',12,'[\"Office Partitioning & Ceilings\",\"Interior & Exterior Premium Paint\",\"Epoxy, Vinyl & Tile Flooring\",\"Custom Cabinetry & Carpentry\"]',NULL,'[\"Architectural Team Overseeing\",\"Berger\\/Asian Paints Partner\"]',0,1,9,1,'2026-10-12 06:23:50','2026-10-12 06:23:50');
UNLOCK TABLES;
LOCK TABLES `amc_plans` WRITE;
INSERT INTO `amc_plans` VALUES (1,'Standard AMC','standard-amc',NULL,'residential','Ideal for residential apartments & medium corporate offices',NULL,'Monthly (customized)','60-Minute Emergency Dispatch SLA',0,'[\"Quarterly Electrical & Switchboard Thermal Audit\",\"Bi-Annual Split\\/Cassette AC Master Jet Servicing\",\"Plumbing Pressure Balancing & Reservoir Sanitization\",\"Monthly Fire Extinguisher Gauge Audits & Alarms\",\"Guaranteed 60-Minute Emergency Dispatch SLA\"]','Inquire Plan',1,1,'2026-10-12 06:23:50','2026-11-15 01:36:37'),(2,'Corporate Comprehensive','corporate-comprehensive','★ Most Popular Enterprise Choice','corporate_enterprise','Tailored for commercial towers, IT tech parks & corporate HQs',NULL,'/ custom enterprise','Priority 45-Minute Emergency Response SLA',1,'[\"Dedicated On-Site Facility Lead & Engineering Supervisor\",\"Central Chillers, AHU\\/FCU & VRF Preventive Care\",\"Generator, ATS Switch & Substation Load Testing\",\"BNBC & NFPA Certified Fire Hydrant & Alarm Testing\",\"Daily Uniformed Janitorial & Facility Workforce\",\"Guaranteed Priority 45-Minute Emergency Response SLA\"]','Request Consultation',2,1,'2026-10-12 06:23:50','2026-10-29 11:55:37'),(3,'Industrial & EPZ Plan','industrial-amc',NULL,'industrial_manufacturing','Engineered for factories, pharma cleanrooms & manufacturing',NULL,'/ tailored industrial','30-Minute Heavy Industrial SLA',0,'[\"High-Tension Substation & 3-Phase Power Balancing\",\"Heavy Industrial Water Booster & Effluent Pumps\",\"Certified Fire Sprinkler Maintenance & Drills\",\"HACCP Compliant Pest Control & Rodent Barriers\",\"Monthly Digital Health Reports & Zero Cost Overruns\"]','Inquire Plan',3,1,'2026-10-12 06:23:50','2026-10-29 11:55:57');
UNLOCK TABLES;
LOCK TABLES `sectors` WRITE;
INSERT INTO `sectors` VALUES (1,'Residential Complexes & Apartments','residential-complexes','home','Society management, shared elevator/generator maintenance, water reservoir care, and residential repairs.','100+ Gated Communities Managed',NULL,NULL,1,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(2,'Corporate Offices','corporate-offices','briefcase','Daily janitorial staffing, HVAC climate comfort, structured electrical distribution, and modern access control.','99.98% Facility Uptime',NULL,NULL,2,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(3,'Hospitals & Clinics','hospitals-clinics','activity','Clinical-grade infection sanitization, medical gas line audits, uninterrupted power failover, and bio-waste safety.','Bio-Safety Level 2 Compliant',NULL,NULL,3,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(4,'Educational Institutions','educational-institutions','book-open','School and university campus facility management, fire drill protocols, hygiene facilities, and electrical safety.','30+ Campus Partners',NULL,NULL,4,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(5,'Government Buildings','government-buildings','award','Structured facility contracts adhering to safety and environmental governance guidelines.','Standard Governance Compliant',NULL,NULL,5,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(6,'Industrial Zones & EPZ Facilities','industrial-epz','layers','High-voltage switchgear, fire hydrant infrastructure, factory floor scrubbing, and wastewater plant care.','Heavy Industry Audited',NULL,NULL,6,1,'2026-10-12 06:23:50','2026-10-12 06:23:50');
UNLOCK TABLES;
LOCK TABLES `faqs` WRITE;
INSERT INTO `faqs` VALUES (1,'services','What areas does FIX currently cover in Bangladesh?','We provide full on-demand service and 24/7 emergency dispatch across Dhaka Metro (Gulshan, Banani, Dhanmondi, Uttara, Mirpur, Motijheel, Mohakhali, etc.), Savar, Gazipur, and Narayanganj. For corporate AMCs, we also cover Chattogram EPZ and other key industrial zones.',1,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(2,'safety','How are service providers and technicians verified?','Every technician and vendor undergoes strict multi-step vetting: National Identity (NID) check, police background verification, technical competency testing, and trade license audits. All technicians wear standard FIX safety gear and PPE.',2,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(3,'amc','What is an Annual Maintenance Contract (AMC) and how does it help?','An AMC with FIX is a comprehensive agreement where our engineering team handles regular preventive inspections (HVAC, electrical, plumbing, generators, fire systems) and provides guaranteed emergency response, lowering long-term repair costs by 35-50%.',3,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(4,'services','What is the emergency turnaround time for urgent breakdowns?','For urgent electrical failures, major plumbing bursts, or HVAC shut-offs across Dhaka, our rapid emergency teams deploy within 45 to 90 minutes. Corporate AMC clients receive priority dedicated response under strict SLA.',4,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(5,'billing','How does billing and pricing work? Are there hidden charges?','FIX follows 100% transparent pricing. After diagnosing the scope, our engineers provide an itemized upfront digital quotation before any technical work begins. You receive a verified digital invoice and service completion report.',5,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(6,'billing','Can I apply to become an enlisted service provider or partner?','Yes! We are continuously expanding our partner network. Registered contractors, HVAC specialists, plumbing companies, and electricians can apply via our Become a Partner portal to undergo verification and receive institutional work orders.',6,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(7,'amc','Do you offer tailored facility maintenance plans for commercial towers?','Yes. Our senior engineers conduct complimentary on-site audits of your commercial building, evaluating generator capacities, HVAC tonnage, substation health, and elevator systems to engineer a tailored SLA maintenance agreement.',7,1,'2026-10-12 06:23:50','2026-10-12 06:23:50');
UNLOCK TABLES;
LOCK TABLES `testimonials` WRITE;
INSERT INTO `testimonials` VALUES (1,'Farhan Abir','Head of Operations','Apex Capital Tower (Banani)','testimonials/qT1VTTGPw4q6wlsuY3U5bIABQZJwGua40kLgCZTn.png',5,'Managing HVAC, generator maintenance, and janitorial staff across our 12-story commercial tower in Banani used to be a nightmare of disconnected vendors. FIX took over our complete AMC and reduced downtime by over 80%.',1,1,1,'2026-10-12 06:23:50','2026-10-28 23:42:23'),(2,'Nusrat Jahan','Secretary','Gulshan Park View Residents Society','https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80',5,'When our apartment complex experienced a major underground pipe burst, FIX dispatched their leak detection engineers within 40 minutes. They repaired the pressurized water line cleanly without damaging our tiles.',1,2,1,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(3,'Engr. Rafiqul Islam','Plant Facility Manager','Gazipur Industrial Park','https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80',5,'Their specialized sanitization and fire safety compliance teams are exceptionally professional. They provide detailed inspection logs that pass all international compliance audits with zero remarks.',1,3,1,'2026-10-12 06:23:50','2026-10-12 06:23:50');
UNLOCK TABLES;
LOCK TABLES `site_settings` WRITE;
INSERT INTO `site_settings` VALUES (1,'site_name','FIX Facility Management','general','text','Site Name',NULL,'2026-10-12 06:23:50','2026-11-14 01:48:18'),(2,'site_tagline','Clean • Safe • Sustainable','general','text','Tagline',NULL,'2026-10-12 06:23:50','2026-11-14 01:48:18'),(3,'emergency_phone','+880 1700-000000','contact','text','Emergency Hotline',NULL,'2026-10-12 06:23:50','2026-11-14 01:48:18'),(4,'support_email','support@fix.com.bd','contact','text','Support Email',NULL,'2026-10-12 06:23:50','2026-11-14 01:48:18'),(5,'head_office_address','Plot 12, Road 11, Block D, Banani, Dhaka-1213, Bangladesh','contact','text','Head Office Address',NULL,'2026-10-12 06:23:50','2026-11-14 01:48:18'),(6,'operating_hours','General Support: 8:00 AM – 10:00 PM (Daily)\nEmergency Callouts: 24/7 Available','contact','text','Operating Hours',NULL,'2026-10-12 06:23:50','2026-11-14 01:48:18'),(7,'header_logo','assets/images/logo-dark-crop.png','branding','text','Header Dark Logo',NULL,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(8,'footer_logo','assets/images/logo-white-crop.png','branding','text','Footer Light Logo',NULL,'2026-10-12 06:23:50','2026-10-12 06:23:50'),(9,'meta_title','FIX - Facility Management & Maintenance Services in Bangladesh','seo','text','Default SEO Title',NULL,'2026-10-12 06:23:50','2026-11-14 01:48:18'),(10,'meta_description','Professional facility management, HVAC chillers, electrical, plumbing, fire safety & AMC services in Dhaka, Bangladesh.','seo','text','Default Meta Description',NULL,'2026-10-12 06:23:50','2026-11-14 01:48:18'),(11,'favicon','assets/images/favicon-white.png','brand','image','Favicon',NULL,'2026-10-23 01:40:00','2026-10-23 01:40:00'),(12,'mail_mailer','smtp','general','text','Mail Mailer',NULL,'2026-10-24 04:10:16','2026-10-24 04:39:07'),(13,'mail_host','smtp.gmail.com','general','text','Mail Host',NULL,'2026-10-24 04:10:16','2026-10-24 04:39:07'),(14,'mail_port','587','general','text','Mail Port',NULL,'2026-10-24 04:10:16','2026-10-24 04:39:07'),(15,'mail_encryption','tls','general','text','Mail Encryption',NULL,'2026-10-24 04:10:16','2026-10-24 04:10:16'),(16,'mail_from_address','easourav@gmail.com','general','text','Mail From Address',NULL,'2026-10-24 04:10:16','2026-10-24 04:39:07'),(17,'mail_username','easourav@gmail.com','general','text','Mail Username',NULL,'2026-10-24 04:10:16','2026-10-24 04:39:07'),(18,'mail_password','faec azqt vlwr tjav','general','text','Mail Password',NULL,'2026-10-24 04:10:16','2026-10-24 04:39:07'),(19,'mail_from_name','FIX','general','text','Mail From Name',NULL,'2026-10-24 04:10:16','2026-10-26 10:25:03'),(20,'facebook_url','https://facebook.com/fixfacility','social','text','Facebook Page URL',NULL,'2026-10-27 12:36:14','2026-11-14 01:48:18'),(21,'linkedin_url','https://linkedin.com/company/fixfacility','social','text','LinkedIn Page URL',NULL,'2026-10-27 12:36:14','2026-11-14 01:48:18'),(22,'instagram_url','https://instagram.com/fixfacility','social','text','Instagram Profile URL',NULL,'2026-10-27 12:36:14','2026-11-14 01:48:18'),(23,'youtube_url','https://youtube.com/@fixfacility','social','text','YouTube Channel URL',NULL,'2026-10-27 12:36:14','2026-11-14 01:48:18'),(24,'whatsapp_number','+8801700000000','social','text','WhatsApp Number',NULL,'2026-10-27 12:36:14','2026-11-14 01:48:18'),(25,'site_description','FIX is your trusted partner for complete building maintenance and facility management solutions in Bangladesh. We protect property value and enhance liveability through certified engineering.','general','textarea','Site Description',NULL,'2026-10-28 02:10:42','2026-10-28 07:48:47'),(26,'admin_email','admin@fix.com.bd','general','text','Admin Email',NULL,'2026-10-28 02:10:42','2026-10-28 02:10:42'),(27,'copyright_text','© 2026 FIX  All Rights Reserved.','general','text','Copyright Text',NULL,'2026-10-28 02:10:42','2026-10-28 07:04:14'),(28,'maintenance_mode','0','general','text','Maintenance Mode',NULL,'2026-10-28 02:10:42','2026-10-28 02:10:42'),(29,'maintenance_message','We are currently performing scheduled maintenance to upgrade our systems. We will be back online shortly.','general','text','Maintenance Message',NULL,'2026-10-28 02:10:42','2026-10-28 02:10:42'),(30,'search_engine_visibility','1','general','text','Search Engine Visibility',NULL,'2026-10-28 02:10:42','2026-10-28 02:10:42'),(31,'cookie_consent_enabled','1','general','text','Cookie Consent Enabled',NULL,'2026-10-28 02:10:42','2026-10-28 02:10:42'),(32,'terms_page_slug','terms','general','text','Terms Page Slug',NULL,'2026-10-28 02:10:42','2026-10-28 02:10:42'),(33,'privacy_page_slug','privacy','general','text','Privacy Page Slug',NULL,'2026-10-28 02:10:42','2026-10-28 02:10:42'),(34,'facebook_active','1','social','boolean','Facebook Active',NULL,'2026-10-28 07:48:47','2026-10-28 23:37:25'),(35,'linkedin_active','1','social','boolean','LinkedIn Active',NULL,'2026-10-28 07:48:47','2026-10-28 07:48:47'),(36,'instagram_active','1','social','boolean','Instagram Active',NULL,'2026-10-28 07:48:47','2026-10-28 07:48:47'),(37,'youtube_active','1','social','boolean','YouTube Active',NULL,'2026-10-28 07:48:47','2026-10-28 07:48:47'),(38,'whatsapp_active','1','social','boolean','WhatsApp Active',NULL,'2026-10-28 07:48:47','2026-10-28 07:48:47'),(39,'coverage_area','Dhaka North, Dhaka South, Gazipur, Savar, EPZ & Major Industrial Zones','contact','text','Service Coverage Area',NULL,'2026-10-31 01:11:21','2026-11-14 01:48:18'),(40,'email_header_ticket_created','Engineering Helpdesk Confirmation','email','text','Ticket Created Email Subtitle',NULL,'2026-11-14 01:48:18','2026-11-14 01:48:18'),(41,'email_header_ticket_reply','Engineering Helpdesk — New Response','email','text','Ticket Admin Reply Email Subtitle',NULL,'2026-11-14 01:48:18','2026-11-14 01:48:18'),(42,'email_header_otp','Clean • Safe • Sustainable Engineering','email','text','OTP Email Subtitle',NULL,'2026-11-14 01:48:18','2026-11-14 01:48:18'),(43,'email_header_test_smtp','Admin Command Center','email','text','SMTP Test Email Subtitle',NULL,'2026-11-14 01:48:18','2026-11-14 01:48:18');
UNLOCK TABLES;
LOCK TABLES `site_contents` WRITE;
INSERT INTO `site_contents` VALUES (1,'hero',1,'Your Trusted Partner for <span>Every Building</span> Service','Certified engineering, rapid dispatch, and transparent facility operations across Bangladesh.','24/7 NATIONWIDE SERVICE DISPATCH','Book a Service','/booking','Explore Services','#services','uploads/hero/slide_1.jpg','slides','{\"slides\":[\"uploads\\/hero\\/slide_1.jpg\",\"uploads\\/hero\\/slide_2.jpg\",\"uploads\\/hero\\/slide_3.jpg\"],\"floating_badge_title\":\"Quality Guaranteed\",\"floating_badge_text\":\"100% Verified Technical Teams & Rigorous Safety\",\"trust_badges\":[\"Verified & Certified Providers\",\"Quick Service Request & Dispatch\",\"Multiple Building Solutions\",\"24\\/7 Dedicated Operations Support\"]}','2026-10-12 06:23:50','2026-10-24 03:00:38'),(2,'video_portfolio',1,'Watch FIX in Action: <span>Real Project Operations</span>','Real facility interventions executed by our certified mechanical, electrical, and HVAC teams.','PROJECT SHOWCASE','Request Site Inspection','/booking',NULL,NULL,'https://www.youtube.com/embed/LXb3EKWsInQ?rel=0','embed','{\"default_title\":\"Commercial Central HVAC & Chiller Overhaul\",\"default_meta\":\"Standard Chartered Tower \\u2022 Banani, Dhaka\"}','2026-10-12 06:23:50','2026-10-24 03:00:38'),(3,'clients',1,'Trusted by Leading Enterprises & Facilities','Over 250+ Commercial Towers, Financial Institutions, and Industrial Complexes Managed.','CORPORATE TRUST',NULL,NULL,NULL,NULL,NULL,'text','{\"partner_names\":[\"Standard Chartered Tower\",\"Grameenphone GPHouse\",\"Shanta Holdings Towers\",\"Apex Footwear Complex\",\"United Hospital Complex\",\"Square Pharmaceuticals\",\"Beximco Industrial Park\",\"BRAC Centre & Campus\"],\"clients\":[{\"name\":\"Standard Chartered Tower\",\"logo\":\"uploads\\/clients\\/standard_chartered.svg\"},{\"name\":\"Grameenphone GPHouse\",\"logo\":\"uploads\\/clients\\/grameenphone.svg\"},{\"name\":\"Shanta Holdings Towers\",\"logo\":\"uploads\\/clients\\/shanta_holdings.svg\"},{\"name\":\"Apex Footwear Complex\",\"logo\":\"uploads\\/clients\\/apex_footwear.svg\"},{\"name\":\"United Hospital Complex\",\"logo\":\"uploads\\/clients\\/united_hospital.svg\"},{\"name\":\"Square Pharmaceuticals\",\"logo\":\"uploads\\/clients\\/square_pharma.svg\"},{\"name\":\"Beximco Industrial Park\",\"logo\":\"uploads\\/clients\\/beximco.svg\"},{\"name\":\"BRAC Centre & Campus\",\"logo\":\"uploads\\/clients\\/brac.svg\"}]}','2026-10-24 03:00:38','2026-10-28 05:34:53'),(4,'services',1,'Our Core Maintenance Capabilities',NULL,'SERVICES & SOLUTIONS','Explore All Services','#services',NULL,NULL,NULL,'grid',NULL,'2026-10-24 03:00:38','2026-10-24 03:00:38'),(5,'amc_plans',1,'Annual Maintenance Contracts <span>(AMC)</span>',NULL,'PREVENTIVE CARE','Choose AMC Package','#amc',NULL,NULL,NULL,'grid',NULL,'2026-10-24 03:00:38','2026-10-28 01:38:13'),(6,'how_it_works',1,'How It Works',NULL,'STREAMLINED PROCESS','Book Service Now','/booking',NULL,NULL,NULL,'steps',NULL,'2026-10-24 03:00:38','2026-11-14 01:48:18'),(7,'why_fix',1,'Why Choose FIX?',NULL,'OUR VALUE PROPOSITION',NULL,NULL,NULL,NULL,NULL,'grid',NULL,'2026-10-24 03:00:38','2026-10-28 23:29:09'),(8,'who_we_serve',1,'Who We Serve',NULL,'DEDICATED CLIENTELE',NULL,NULL,NULL,NULL,NULL,'grid',NULL,'2026-10-24 03:00:38','2026-10-24 03:00:38'),(9,'testimonials',1,'What Our Clients Say',NULL,'CLIENT REVIEWS',NULL,NULL,NULL,NULL,NULL,'slider',NULL,'2026-10-24 03:00:38','2026-10-24 03:00:38'),(10,'faq',1,'Frequently Asked Questions',NULL,'HELP & SUPPORT',NULL,NULL,NULL,NULL,NULL,'accordion',NULL,'2026-10-24 03:00:38','2026-10-28 23:24:24'),(11,'contact',1,'Get in Touch with Our Operations Command',NULL,'OPERATIONS COMMAND','Send Message','#contact',NULL,NULL,NULL,'form','{\"card_title\":\"Operations Command & Technical Support\",\"card_desc\":\"Have an urgent maintenance request or need facility engineering assistance? Contact our central operations center 24\\/7.\"}','2026-10-24 03:00:38','2026-11-14 01:48:18'),(12,'vendor_hero',1,'Grow Your Facility Service Business with <span>Institutional Work Orders</span>','Partner with Bangladesh\'s unified facility operations network. Gain steady access to corporate towers, hospitals, factories, and commercial complexes with guaranteed bi-weekly payouts and zero customer acquisition cost.','SERVICE PARTNER ENLISTMENT','Apply for Enlistment','#vendorFormSection','Hotline Support','tel:+8801700000000','https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=800&q=80','image','{\"stat_partners\":\"500+\",\"stat_partners_label\":\"Enlisted Vendor Partners\",\"stat_payouts\":\"\\u09f3 2.5 Cr+\",\"stat_payouts_label\":\"Partner Payouts Disbursed\",\"stat_verification\":\"24-48h\",\"stat_verification_label\":\"Fast-Track Verification\",\"floating_badge_top_title\":\"Guaranteed Corporate Payouts\",\"floating_badge_top_text\":\"Direct Bank & bKash Bi-Weekly Settlement\",\"floating_badge_bottom_title\":\"4.9 Partner Rating\",\"floating_badge_bottom_text\":\"98% Contractor Retention Across Dhaka & EPZ\"}','2026-10-28 01:38:13','2026-11-14 01:48:18'),(13,'vendor_benefits',1,'Why Top Technical Contractors Partner with FIX','We manage corporate sales, compliance, client billing, and dispatch — so you can focus 100% on quality service execution.','PARTNER BENEFITS',NULL,NULL,NULL,NULL,NULL,'cards','{\"perks\":[{\"title\":\"Consistent Commercial Orders\",\"description\":\"Gain direct access to corporate headquarters, hospitals, EPZ industrial facilities, and luxury residences without spending on marketing.\"},{\"title\":\"Guaranteed Timely Payments\",\"description\":\"Transparent job completion verification with automatic bi-weekly or monthly disbursements directly to your company bank or bKash account.\"},{\"title\":\"Free Safety Gear & ID Kits\",\"description\":\"Enlisted partner technicians receive standardized FIX professional uniform vests, high-altitude harnesses, safety kits, and verified photo ID badges.\"}]}','2026-10-28 01:38:13','2026-10-28 01:38:13'),(14,'legal_privacy',1,'Privacy Policy','Information collection, data security, cookie tracking, and data protection practices across our platform.',NULL,NULL,NULL,NULL,NULL,NULL,'text','{\"sections\":[{\"heading\":\"1. Information We Collect\",\"body\":\"When you use FIX, request building facility dispatches, subscribe to Annual Maintenance Contracts (AMC), or communicate with our operations center, we may collect personal details such as your name, corporate organization, email address, phone number, physical site address, and equipment logs.\"},{\"heading\":\"2. How We Use Your Data\",\"body\":\"We utilize your data to dispatch certified engineering technicians, provide booking status notifications, generate invoices, perform scheduled preventive maintenance, and safeguard security across all operational interactions.\"},{\"heading\":\"3. Cookies & Analytical Tracking\",\"body\":\"Our platform utilizes cookies to remember session authentication, optimize navigation performance, and analyze operational trends. You may control cookie preferences through browser settings or our on-page cookie consent banner.\"},{\"heading\":\"4. Contacting Data Protection\",\"body\":\"For inquiries regarding privacy, data access requests, or policy clarification, please contact our administrative operations center at support@fix.com.bd or call our hotline.\"}]}','2026-10-28 01:38:13','2026-10-28 03:10:23'),(15,'legal_terms',1,'Terms of Service','Standard commercial terms and conditions governing facility booking, subscriptions, and work orders.',NULL,NULL,NULL,NULL,NULL,NULL,'text','{\"sections\":[{\"heading\":\"1. Scope of Services\",\"body\":\"FIX Facility Management Services provides professional facility engineering, electrical maintenance, commercial HVAC services, fire safety inspections, plumbing dispatches, and scheduled Annual Maintenance Contracts (AMC) across Bangladesh.\"},{\"heading\":\"2. Service Booking & Dispatch Agreements\",\"body\":\"Booking estimates provided via the online portal represent initial scope assessments. Final service costs are confirmed upon physical site diagnostic inspection by certified lead technicians. All work orders follow approved safety guidelines and industry SLA standards.\"},{\"heading\":\"3. Annual Maintenance Contracts (AMC)\",\"body\":\"AMC plan subscriptions are billed per tier terms (Monthly or Annual billing cycle). Subscribers enjoy scheduled preventive inspections, emergency priority dispatches, and dedicated customer support desk access.\"},{\"heading\":\"4. Support & Resolution\",\"body\":\"For support requests, service guarantees, or contract terms inquiries, reach out to our team at support@fix.com.bd.\"}]}','2026-10-28 01:38:13','2026-10-28 03:10:23'),(16,'legal_sla',1,'Service Level Agreement (SLA) & Guarantee','Guaranteed emergency response dispatch times, routine maintenance schedules, and 30-day workmanship warranty.',NULL,NULL,NULL,NULL,NULL,NULL,'text','{\"sections\":[{\"heading\":\"1. Emergency Response Dispatch SLA\",\"body\":\"For critical facility emergencies\\u2014including primary electrical tripping, severe plumbing burst flooding, central HVAC chiller shutoffs, and fire safety alarms\\u2014FIX guarantees lead technician dispatch within 60 minutes across metropolitan Dhaka and regional economic zones.\"},{\"heading\":\"2. Scheduled Preventive Maintenance SLA\",\"body\":\"All Annual Maintenance Contract (AMC) visits are scheduled 48 hours in advance. Preventative routine inspections follow standardized BNBC engineering checklists, and digital inspection logs are delivered to property managers within 24 hours of task completion.\"},{\"heading\":\"3. 30-Day Certified Workmanship Guarantee\",\"body\":\"All non-cosmetic electrical repairs, plumbing fittings, HVAC servicing, and mechanical installations performed by certified FIX field technicians include a 30-day quality and workmanship warranty. If a recurring defect arises within the guarantee window under standard operational parameters, our crew returns for rectification without additional diagnostic dispatch charges.\"},{\"heading\":\"4. Verified Engineering & Safety Standards\",\"body\":\"All dispatched field staff undergo police background verification, technical vetting, and OSHA safety protocol training. We maintain rigorous standards to ensure the highest safety and reliability for commercial facilities and residential properties.\"},{\"heading\":\"5. Escalations & Support SLA\",\"body\":\"For SLA inquiries, guarantee claims, or operations escalation, contact our 24\\/7 Operations Command Desk at support@fix.com.bd or call our emergency hotline.\"}]}','2026-10-28 01:38:13','2026-10-28 03:10:23'),(17,'support_desk',1,'Submit a Support Ticket','Need facility maintenance or emergency engineering assistance? Submit your request below for rapid dispatch.','24/7 CUSTOMER HELPDESK','Track Existing Ticket','/support/track',NULL,NULL,NULL,'text',NULL,'2026-10-28 01:38:13','2026-11-14 01:48:18'),(18,'about_hero',1,'Pioneering Modern Facility Engineering in Bangladesh','FIX is Bangladesh\'s premier technology-driven facility management company, delivering MEP engineering, HVAC solutions, and compliant operations for commercial towers, factories, and residences.',NULL,'Explore Our Services','/#services','Get in Touch','/support',NULL,'image','{\"stat_experience\":\"12+\",\"stat_experience_label\":\"Years of Engineering Excellence\",\"stat_clients\":\"150+\",\"stat_clients_label\":\"Enterprise Facilities Managed\",\"stat_technicians\":\"250+\",\"stat_technicians_label\":\"Certified Engineers & Technicians\",\"stat_satisfaction\":\"99.4%\",\"stat_satisfaction_label\":\"SLA Reliability & Compliance Rate\",\"story_title\":\"Building Bangladesh\'s Most Trusted Facility Infrastructure\",\"story_p1\":\"Founded with a clear vision to revolutionize property care, FIX provides end-to-end building operations. From certified electrical compliance and chiller overhauls to green building sustainability and round-the-clock emergency dispatch, we keep critical infrastructure running flawlessly.\",\"story_p2\":\"Headquartered in Banani, Dhaka, our rapid dispatch technical squads cover Dhaka Metropolitan, Gazipur, Savar, Narayanganj, and major Export Processing Zones (EPZ).\"}','2026-10-28 05:34:53','2026-11-14 01:48:18'),(19,'about_overview',1,'Our Mission, Vision & Core Engineering Principles',NULL,NULL,'Book a Facility Inspection','/booking','Become a Service Partner','/vendor',NULL,'text','{\"mission_title\":\"Our Mission\",\"mission_desc\":\"To elevate facility management standards across Bangladesh through certified technical expertise, 24\\/7 rapid response, and zero-compromise environmental and occupational safety.\",\"vision_title\":\"Our Vision\",\"vision_desc\":\"To be the most reliable, tech-enabled, and sustainable integrated facility management partner for world-class enterprises, export zones, and commercial properties.\",\"values\":[{\"title\":\"BNBC & NFPA Certified Safety\",\"desc\":\"Strict adherence to national and international building codes, fire safety, and electrical hazard prevention.\"},{\"title\":\"2-Hour Emergency SLA\",\"desc\":\"Rapid technical squads stationed at regional hubs for immediate crisis mitigation and critical dispatch.\"},{\"title\":\"Transparent Digital Tracking\",\"desc\":\"Every maintenance request is digitally documented with verified before-after logs and satisfaction tracking.\"},{\"title\":\"Eco-Friendly & Sustainable\",\"desc\":\"Energy-efficient HVAC optimization, non-toxic commercial chemicals, and preventive asset lifecycle planning.\"}],\"milestones\":[{\"year\":\"2014\",\"title\":\"MEP Operations Founded\",\"desc\":\"Launched specialized electrical and mechanical facility engineering services in Dhaka.\"},{\"year\":\"2018\",\"title\":\"Enterprise Expansion\",\"desc\":\"Expanded to 50+ corporate headquarters and EPZ manufacturing facilities.\"},{\"year\":\"2022\",\"title\":\"24\\/7 Command Center\",\"desc\":\"Built our real-time centralized emergency dispatch control room.\"},{\"year\":\"2026\",\"title\":\"Unified Digital Platform\",\"desc\":\"Integrated self-service customer booking, transparent AMC tracking, and digital vendor ecosystem.\"}]}','2026-10-28 05:34:53','2026-10-28 06:57:21'),(20,'partner_terms',1,'Crew & Partner Terms of Service','Operational terms, dispatch acceptance standards, technician obligations, and platform partnership agreements.','CREW CONTRACT',NULL,NULL,NULL,NULL,NULL,'text','{\"sections\":[{\"heading\":\"1. Contractor Onboarding & Identity Verification\",\"body\":\"All field crew and partner technicians must submit valid National ID (NID) or Passport, verified trade licenses, technical certifications, and pass mandatory police background clearance prior to account activation and work order dispatch.\"},{\"heading\":\"2. Work Order Acceptance & Arrival SLAs\",\"body\":\"Once an assigned or open work order is accepted through the Partner Portal, the technician is expected to arrive at the designated customer facility within the agreed arrival window. Delays exceeding 15 minutes must be communicated immediately through the dispatch desk.\"},{\"heading\":\"3. Standard Tooling, PPE & Identification\",\"body\":\"Crew members must arrive equipped with certified multi-meters, insulated hand tools, high-visibility FIX uniform vests, safety footwear, and an active digital or physical FIX photo ID badge prominently displayed at all times.\"},{\"heading\":\"4. Transparent Quotations & Authentic Spare Parts\",\"body\":\"Only genuine replacement components approved by FIX and accepted by the client may be installed. Unreported surcharges, off-platform cash transactions, or deceptive scope expansion are strictly prohibited and will result in immediate contract cancellation.\"},{\"heading\":\"5. Non-Circumvention & Client Exclusivity\",\"body\":\"Field crew members and subcontracted partners agree not to solicit or accept direct off-platform service engagements from FIX residential or corporate clients introduced through the platform for a period of 12 months following assignment.\"}]}','2026-11-14 01:48:18','2026-11-14 01:48:18'),(21,'partner_work_policy',1,'Crew Work Policy & Safety SOP','Standard operating procedures, BNBC electrical and HVAC safety compliance, customer etiquette, and site conduct.','SAFETY & CONDUCT',NULL,NULL,NULL,NULL,NULL,'text','{\"sections\":[{\"heading\":\"1. Uniform Code & Professional Appearance\",\"body\":\"All technicians must present a neat, professional appearance wearing the company-issued FIX uniform vest, clean safety shoes, and photo badge. Personal Protective Equipment (PPE) including helmets, goggles, and cut-resistant gloves must be worn according to the job hazards.\"},{\"heading\":\"2. Electrical & Mechanical Lockout\\/Tagout (LOTO)\",\"body\":\"Before servicing high-voltage distribution panels, industrial chillers, or pressurized hydraulic lines, technicians must enforce strict Lockout\\/Tagout procedures. De-energized circuits must be tested with calibrated voltage detectors before physical contact.\"},{\"heading\":\"3. Site Cleanliness & Property Protection\",\"body\":\"Protective drop cloths must be laid beneath equipment before servicing ceiling AC units, conduits, or plumbing fixtures. Following service completion, the work area must be vacuumed, degreased, and left cleaner than originally found.\"},{\"heading\":\"4. Customer Etiquette & Code of Conduct\",\"body\":\"Communicate with facility managers and homeowners courteously and professionally. Always explain diagnostic findings and receive client approval before starting repairs. Smoking, chewing betel leaf\\/tobacco, resting on client furniture, or using residential facilities is strictly prohibited.\"},{\"heading\":\"5. Zero-Tolerance Safety Violations\",\"body\":\"Reporting to work under the influence of alcohol or narcotics, engaging in verbal or physical confrontation, theft, or deliberate safety bypass results in instantaneous contract termination, security blacklisting, and referral to law enforcement.\"}]}','2026-11-14 01:48:18','2026-11-14 01:48:18'),(22,'partner_privacy',1,'Crew Privacy & Data Protection Policy','Guidelines governing technician personal data, dispatch location telemetry, and strict client confidentiality standards.','PRIVACY & SECURITY',NULL,NULL,NULL,NULL,NULL,'text','{\"sections\":[{\"heading\":\"1. Technician Personal Data Protection\",\"body\":\"FIX securely stores contractor identity verification documents, bank account details, payout histories, and background check records in encrypted storage. This information is processed exclusively for payroll, compliance, and regulatory audits.\"},{\"heading\":\"2. Real-Time Dispatch GPS Telemetry\",\"body\":\"Location telemetry is collected through mobile devices solely while a technician has an active work order in \\\"En Route\\\" or \\\"In Progress\\\" status. This data is used to provide accurate client arrival times and ensure lone-worker safety during emergency dispatches.\"},{\"heading\":\"3. Strict Client Non-Disclosure & Privacy\",\"body\":\"Technicians are granted temporary access to client addresses, contact numbers, and building entry instructions solely for job fulfillment. Crew members are strictly forbidden from photographing private residential spaces, recording conversations, or disclosing client details to third parties.\"},{\"heading\":\"4. Financial & Payout Record Confidentiality\",\"body\":\"Individual partner earnings, commission rates, incentive bonuses, and payout requests are treated as confidential financial records and will never be disclosed to other partners or unauthorized staff.\"}]}','2026-11-14 01:48:18','2026-11-14 01:48:18'),(23,'partner_payout_policy',1,'Crew Payout & Earnings Settlement Policy','Rules governing job earnings, commission splits, payout withdrawal thresholds, processing turnaround, and warranty reserves.','PAYOUT & EARNINGS',NULL,NULL,NULL,NULL,NULL,'text','{\"sections\":[{\"heading\":\"1. Earning Accrual & Transparent Breakdown\",\"body\":\"Work order earnings are credited to the partner\'s available balance immediately upon digital customer sign-off and administrative job closure. The payout ledger reflects net earnings after agreed platform revenue shares.\"},{\"heading\":\"2. Minimum Payout Threshold & Request Cycles\",\"body\":\"Partners may request withdrawal of accumulated earnings anytime the available balance reaches or exceeds \\u09f3500. Payout requests submitted on business days before 4:00 PM are processed within 24 to 48 hours.\"},{\"heading\":\"3. Supported Receiving Methods\",\"body\":\"Disbursements can be routed to verified Mobile Financial Services (bKash, Nagad, Rocket, Upay) or direct Electronic Fund Transfer (BEFTN\\/NPSB) to registered Bangladeshi commercial bank accounts. Technicians may set a preferred primary payout method in Settings.\"},{\"heading\":\"4. 30-Day Workmanship Warranty Recalls & Deductions\",\"body\":\"Under our 30-day workmanship guarantee, if a client experiences a recurring service failure caused by technician assembly or installation defect, the original crew is dispatched for complimentary rectification. Negligent failures or unfulfilled recalls may result in adjustment against pending payouts.\"}]}','2026-11-14 01:48:18','2026-11-14 01:48:18');
UNLOCK TABLES;
LOCK TABLES `admin_payment_methods` WRITE;
INSERT INTO `admin_payment_methods` VALUES (1,'bkash','01700000000','FIX Operations HQ','merchant',NULL,NULL,NULL,'Make payment to FIX Merchant bKash. Enter your Crew ID / Booking Number as reference.',1,0,'2026-11-14 04:10:23','2026-11-14 04:35:40'),(2,'bank','1102938475001','FIX Technologies Ltd.',NULL,'The City Bank Limited','Gulshan-2 Branch','225261145','Transfer via BEFTN/NPSB. Keep bank deposit voucher or transaction slip as reference.',1,1,'2026-11-14 04:10:23','2026-11-14 04:35:40'),(3,'offline','CASH-OFFICE-01','Accounts Counter (Banani HQ)','merchant',NULL,NULL,NULL,'Pay cash directly to Accounts Desk at FIX HQ (Level 4, Banani 11, Dhaka). Collect money receipt.',1,0,'2026-11-14 04:10:23','2026-11-14 04:10:23'),(4,'rocket','01811223344-9',NULL,NULL,NULL,NULL,NULL,NULL,1,0,'2026-11-14 04:36:06','2026-11-14 04:36:06');
UNLOCK TABLES;
LOCK TABLES `admins` WRITE;
INSERT INTO `admins` VALUES (1,'FIX Super Admin','admin@fix.com.bd','$2y$12$tvjqAwaA1Z6/jfNEc303hurzSOpaVTfhWbd5CiCVQGyIinYQMpm.W','+880 1700-000000','super_admin','avatars/admins/dsf9DZMrqKXswPtkVJU4mATqBZjpv0MSRMaIeeu8.jpg',1,0,'[\"dashboard.view\",\"bookings.view\",\"bookings.edit\",\"tickets.view\",\"tickets.edit\",\"users.view\",\"users.edit\",\"services.view\",\"services.edit\",\"amc_plans.view\",\"amc_plans.edit\",\"web_content.view\",\"web_content.edit\",\"settings.view\",\"settings.edit\",\"analytics.view\",\"staff_management.view\",\"staff_management.edit\",\"activity_logs.view\"]',NULL,'rbG8eJvlSVcNKhbMJhMApdEZ44pQKTAvetfcWPyiCijTpKZv0EdlEeSi5YPi','2026-10-12 08:54:15','2026-10-29 00:13:44');
UNLOCK TABLES;
LOCK TABLES `migrations` WRITE;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_12_000001_add_profile_fields_to_users_table',2),(5,'2026_10_12_000002_create_email_otps_table',2),(6,'2026_10_12_000003_create_service_categories_and_services_tables',2),(7,'2026_10_12_000004_create_amc_plans_table',2),(8,'2026_10_12_000005_create_sectors_table',2),(9,'2026_10_12_000006_create_faqs_and_testimonials_tables',2),(10,'2026_10_12_000007_create_site_cms_tables',2),(11,'2026_10_12_000008_create_bookings_and_logs_tables',2),(12,'2026_10_12_000009_create_admin_notifications_table',2),(13,'2026_10_12_000010_create_admins_table',3),(14,'2026_10_12_000011_add_admin_id_to_booking_status_logs_table',4),(15,'2026_10_21_062721_add_payload_to_email_otps_table',5),(16,'2026_10_21_081346_create_daily_analytics_table',6),(17,'2026_10_21_085000_add_new_visitors_to_daily_analytics_table',7),(18,'2026_10_23_085507_create_support_tickets_tables',8),(19,'2026_10_23_095307_add_permissions_and_must_change_password_to_admins_table',9),(20,'2026_10_23_101500_create_admin_activity_logs_table',10),(21,'2026_10_24_061758_add_call_and_review_fields_to_bookings_table',11),(22,'2026_10_24_080000_create_bangladesh_locations_tables',12),(23,'2026_10_24_081000_create_partners_table',13),(24,'2026_10_24_091856_add_gallery_images_to_services_table',14),(25,'2026_10_24_093932_make_pricing_unit_nullable_on_services_table',15),(26,'2026_10_28_070931_create_outreach_tasks_table',16),(27,'2026_10_28_080108_add_user_id_to_outreach_tasks_table',17),(28,'2026_10_28_114406_add_is_active_to_site_contents_table',18),(29,'2026_10_28_183758_clear_services_subtitle_in_site_contents',19),(30,'2026_10_29_050602_clear_faq_subtitle_in_site_contents',19),(31,'2026_10_29_051139_clear_homepage_sections_subtitles_in_site_contents',19),(32,'2026_10_29_061500_add_amc_contract_fields_to_bookings_table',19),(33,'2026_10_31_084527_change_trade_domain_to_text_in_partners_table',20),(34,'2026_10_31_114032_add_amc_recurring_billing_to_bookings_and_amc_invoices',21),(35,'2026_11_08_065412_add_auth_and_availability_to_partners_table',22),(36,'2026_11_08_065418_create_partner_leaves_and_crews_tables',23),(37,'2026_11_08_065435_add_partner_dispatch_fields_to_bookings_table',23),(38,'2026_11_08_065456_add_email_notification_preferences_to_admins_table',23),(39,'2026_11_08_124032_add_earning_model_and_payout_rate_to_partners_and_bookings_tables',24),(40,'2026_11_08_144508_create_partner_notifications_table',25),(41,'2026_11_08_165412_add_is_active_to_districts_and_thanas_and_payout_fields_to_bookings',26),(42,'2026_11_09_112823_create_partner_payment_methods_and_payouts_tables',27),(43,'2026_11_13_113943_add_avatar_to_partners_table',28),(44,'2026_11_14_100935_add_payment_collection_fields_to_bookings_and_enhance_payouts_and_admin_methods',29),(45,'2026_11_14_122337_create_amc_shifts_and_notes_and_enhance_amc_bookings',30),(46,'2026_11_15_072819_make_price_period_and_tier_nullable_on_amc_plans_table',31),(47,'2026_11_15_075116_add_contract_tenure_to_bookings_table',32),(48,'2026_11_15_081432_enhance_amc_billing_and_shift_payouts',33);
UNLOCK TABLES;

SET FOREIGN_KEY_CHECKS=1;
