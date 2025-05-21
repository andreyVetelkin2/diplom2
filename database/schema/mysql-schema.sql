/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `authors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `author_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `affiliation` text COLLATE utf8mb4_unicode_ci,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interests` text COLLATE utf8mb4_unicode_ci,
  `cited_by` int NOT NULL DEFAULT '0',
  `google_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `authors_user_id_foreign` (`user_id`),
  CONSTRAINT `authors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Название категории',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `institute_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `departments_institute_id_foreign` (`institute_id`),
  CONSTRAINT `departments_institute_id_foreign` FOREIGN KEY (`institute_id`) REFERENCES `institutes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `field_entry_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `field_entry_values` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `form_entry_id` bigint unsigned NOT NULL,
  `template_field_id` bigint unsigned NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci COMMENT 'Значение, приведённое к строке',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `field_entry_values_form_entry_id_foreign` (`form_entry_id`),
  KEY `field_entry_values_template_field_id_foreign` (`template_field_id`),
  CONSTRAINT `field_entry_values_form_entry_id_foreign` FOREIGN KEY (`form_entry_id`) REFERENCES `form_entries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `field_entry_values_template_field_id_foreign` FOREIGN KEY (`template_field_id`) REFERENCES `template_fields` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `field_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `field_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `template_field_id` bigint unsigned NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Хранимое значение',
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Отображаемый текст',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `field_options_template_field_id_foreign` (`template_field_id`),
  CONSTRAINT `field_options_template_field_id_foreign` FOREIGN KEY (`template_field_id`) REFERENCES `template_fields` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `form_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `form_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `form_template_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `form_id` bigint unsigned DEFAULT NULL,
  `status` enum('review','approved','rejected') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_achievement` date DEFAULT NULL COMMENT 'Дата достижения',
  `comment` text COLLATE utf8mb4_unicode_ci,
  `percent` double(8,2) DEFAULT NULL COMMENT 'Процент участия в достижении',
  PRIMARY KEY (`id`),
  KEY `form_entries_form_template_id_foreign` (`form_template_id`),
  KEY `form_entries_user_id_foreign` (`user_id`),
  KEY `form_entries_form_id_foreign` (`form_id`),
  CONSTRAINT `form_entries_form_id_foreign` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `form_entries_form_template_id_foreign` FOREIGN KEY (`form_template_id`) REFERENCES `form_templates` (`id`) ON DELETE CASCADE,
  CONSTRAINT `form_entries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `form_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `form_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Название шаблона',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Описание шаблона',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Название формы',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Описание формы',
  `category_id` bigint unsigned NOT NULL,
  `points` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Количество баллов',
  `form_template_id` bigint unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Форма активна',
  `single_entry` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Ограничение: только одна запись на пользователя',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Обозначение формы',
  PRIMARY KEY (`id`),
  KEY `forms_category_id_foreign` (`category_id`),
  KEY `forms_form_template_id_foreign` (`form_template_id`),
  CONSTRAINT `forms_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `forms_form_template_id_foreign` FOREIGN KEY (`form_template_id`) REFERENCES `form_templates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `institutes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `institutes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `penalty_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penalty_points` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penalty_points` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles_permissions` (
  `role_id` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `roles_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `roles_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `roles_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `template_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `template_fields` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `form_template_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Уникальный ключ поля, например “start_date”',
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Человекочитабельное название',
  `type` enum('datetime','string','checkbox','list','file') COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `template_fields_form_template_id_foreign` (`form_template_id`),
  CONSTRAINT `template_fields_form_template_id_foreign` FOREIGN KEY (`form_template_id`) REFERENCES `form_templates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','master','client') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Должность',
  `hirsh` int DEFAULT NULL,
  `citations` int DEFAULT NULL,
  `rating` double(8,2) NOT NULL COMMENT 'Рейтинг за текущий квартал',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_department_id_foreign` (`department_id`),
  CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_permissions` (
  `user_id` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`permission_id`),
  KEY `users_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `users_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_roles` (
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `users_roles_role_id_foreign` (`role_id`),
  CONSTRAINT `users_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2024_10_24_130849_create_services_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2024_10_24_130926_create_masters_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2024_10_24_130943_create_appointments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2024_10_24_131015_create_master_service_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2014_10_12_100000_create_password_reset_tokens_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2019_08_19_000000_create_failed_jobs_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2019_12_14_000001_create_personal_access_tokens_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2025_04_01_111433_create_roles_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2025_04_01_111439_create_permissions_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2025_04_01_111642_create_users_permissions_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2025_04_01_111710_create_users_roles_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2025_04_01_111737_create_roles_permissions_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2025_04_22_121203_create_form_templates_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2025_04_22_121512_create_template_fields_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2025_04_22_121540_create_field_options_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2025_04_22_121633_create_form_entries_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2025_04_22_121656_create_field_entry_values_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2025_04_23_131849_create_categories_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2025_04_23_131905_create_forms_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2025_04_25_134410_add_file_columns_to_template_fields',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2025_04_25_134517_update_type_column_in_template_fields',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2025_05_02_100734_add_column_to_form_entries_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2025_05_02_104606_add_column_slug_to_forms_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2025_05_02_113238_add_column_status_to_form_entries_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2025_05_07_114801_add_date_achievement_column_to_form_entries_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2025_04_27_121504_create_authors_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2025_04_27_164343_add_user_id_to_authors_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2025_04_27_165929_fix_authors_user_id_relation',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2025_05_08_100610_add_column_date_achievement_to_form_entries_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2025_05_08_105437_create_institutes_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2025_05_08_105451_create_departments_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2025_05_08_111109_add_column_department_id_to_users_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2025_05_08_130640_add_column_original_name_to_field_entry_values_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2025_05_09_182105_add_column_comment_to_form_entries_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2025_05_11_081655_add_column_position_to_users_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2025_05_11_153923_add_hirsh_to_users_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2025_05_13_111344_add_percent_column_to_form_entry_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2025_05_14_070135_add_rating_column_to_users_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2025_05_14_072447_add_comment_column_to_field_entry_values_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2025_05_14_121808_create_penalty_points_table',14);
