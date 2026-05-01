-- MySQL dump 10.13  Distrib 9.6.0, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: quizapp
-- ------------------------------------------------------
-- Server version	8.0.45

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
-- Table structure for table `answers`
--

DROP TABLE IF EXISTS `answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `answers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question_id` bigint unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `answers_question_id_foreign` (`question_id`),
  CONSTRAINT `answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `answers`
--

LOCK TABLES `answers` WRITE;
/*!40000 ALTER TABLE `answers` DISABLE KEYS */;
INSERT INTO `answers` VALUES (1,1,'Yes',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(2,1,'No',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(3,2,'Yes',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(4,2,'No',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(5,3,'Yes',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(6,3,'No',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(7,4,'Yes',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(8,4,'No',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(9,5,'Yes',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(10,5,'No',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(11,6,'Yes',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(12,6,'No',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(13,7,'Yes',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(14,7,'No',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(15,8,'Yes',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(16,8,'No',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(17,9,'Yes',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(18,9,'No',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(19,10,'Yes',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(20,10,'No',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(21,11,'Yes',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(22,11,'No',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(23,12,'Yes',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(24,12,'No',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(25,13,'Lacus Curabitur',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(26,13,'Officia Deserunt',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(27,13,'Lorem Ipsum',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(28,14,'Officia Deserunt',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(29,14,'Dolor Sit',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(30,14,'Bibendum Orci',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(31,15,'Pharetra Eros',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(32,15,'Amet Consectetur',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(33,15,'Mollit Anim',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(34,16,'Adipiscing Elit',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(35,16,'Lacus Curabitur',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(36,16,'Laborum Sed',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(37,17,'Pharetra Eros',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(38,17,'Officia Deserunt',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(39,17,'Sed Eiusmod',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(40,18,'Pariatur Excepteur',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(41,18,'Officia Deserunt',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(42,18,'Tempor Incididunt',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(43,19,'Pharetra Eros',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(44,19,'Magna Aliqua',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(45,19,'Cupidatat Sunt',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(46,20,'Veniam Nostrud',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(47,20,'Pharetra Eros',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(48,20,'Bibendum Orci',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(49,21,'Cupidatat Sunt',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(50,21,'Ullamco Laboris',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(51,21,'Mollit Anim',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(52,22,'Tristique Convallis',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(53,22,'Aliquip Commodo',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(54,22,'Lacus Curabitur',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(55,23,'Lacus Curabitur',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(56,23,'Reprehenderit Voluptate',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(57,23,'Officia Deserunt',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(58,24,'Cillum Dolore',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(59,24,'Tristique Convallis',0,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(60,24,'Pariatur Excepteur',0,'2026-04-30 14:05:53','2026-04-30 14:05:53');
/*!40000 ALTER TABLE `answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

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

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2023_03_14_121540_create_quizzes_table',1),(6,'2023_03_14_121544_create_questions_table',1),(7,'2023_03_14_121634_create_answers_table',1),(8,'2023_03_16_125133_create_user_quizzes_table',1),(9,'2023_03_16_125242_create_user_quiz_answers_table',1),(10,'2026_04_30_130000_add_type_to_questions_table',1),(11,'2026_04_30_130100_refactor_user_quizzes_for_sessions',1),(12,'2026_04_30_142641_add_mode_to_users_table',1),(13,'2026_04_30_172144_add_last_name_to_users_table',1),(14,'2026_04_30_174933_add_question_ids_to_user_quizzes',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `questions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BINARY',
  `quiz_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_quiz_id_foreign` (`quiz_id`),
  KEY `questions_type_index` (`type`),
  CONSTRAINT `questions_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (1,'Did Lacus Curabitur say: \"Lorem ipsum dolor sit amet, consectetur adipiscing elit.\"?','BINARY',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(2,'Did Cupidatat Sunt say: \"Sed do eiusmod tempor incididunt ut labore et dolore.\"?','BINARY',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(3,'Did Bibendum Orci say: \"Ut enim ad minim veniam, quis nostrud exercitation.\"?','BINARY',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(4,'Did Laborum Sed say: \"Duis aute irure dolor in reprehenderit in voluptate.\"?','BINARY',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(5,'Did Lacus Curabitur say: \"Excepteur sint occaecat cupidatat non proident.\"?','BINARY',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(6,'Did Pharetra Eros say: \"Curabitur pretium tincidunt lacus, nulla gravida orci.\"?','BINARY',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(7,'Did Magna Aliqua say: \"Nullam varius, turpis et commodo pharetra, est eros.\"?','BINARY',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(8,'Did Laborum Sed say: \"Praesent eu nulla at lectus convallis tristique non.\"?','BINARY',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(9,'Did Mollit Anim say: \"Vestibulum ante ipsum primis in faucibus orci luctus.\"?','BINARY',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(10,'Did Mollit Anim say: \"Suspendisse potenti morbi vehicula tellus eu velit.\"?','BINARY',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(11,'Did Lacus Curabitur say: \"Aenean tellus metus, bibendum sed, posuere ac, mattis non.\"?','BINARY',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(12,'Did Cupidatat Sunt say: \"Vivamus quis mi vestibulum laoreet ligula in vehicula.\"?','BINARY',1,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(13,'Who said: \"Lorem ipsum dolor sit amet, consectetur adipiscing elit.\"?','MULTI',2,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(14,'Who said: \"Sed do eiusmod tempor incididunt ut labore et dolore.\"?','MULTI',2,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(15,'Who said: \"Ut enim ad minim veniam, quis nostrud exercitation.\"?','MULTI',2,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(16,'Who said: \"Duis aute irure dolor in reprehenderit in voluptate.\"?','MULTI',2,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(17,'Who said: \"Excepteur sint occaecat cupidatat non proident.\"?','MULTI',2,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(18,'Who said: \"Curabitur pretium tincidunt lacus, nulla gravida orci.\"?','MULTI',2,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(19,'Who said: \"Nullam varius, turpis et commodo pharetra, est eros.\"?','MULTI',2,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(20,'Who said: \"Praesent eu nulla at lectus convallis tristique non.\"?','MULTI',2,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(21,'Who said: \"Vestibulum ante ipsum primis in faucibus orci luctus.\"?','MULTI',2,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(22,'Who said: \"Suspendisse potenti morbi vehicula tellus eu velit.\"?','MULTI',2,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(23,'Who said: \"Aenean tellus metus, bibendum sed, posuere ac, mattis non.\"?','MULTI',2,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(24,'Who said: \"Vivamus quis mi vestibulum laoreet ligula in vehicula.\"?','MULTI',2,'2026-04-30 14:05:53','2026-04-30 14:05:53');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quizzes`
--

DROP TABLE IF EXISTS `quizzes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quizzes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BINARY',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` int NOT NULL DEFAULT '300',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quizzes`
--

LOCK TABLES `quizzes` WRITE;
/*!40000 ALTER TABLE `quizzes` DISABLE KEYS */;
INSERT INTO `quizzes` VALUES (1,'BINARY','Lorem Ipsum — Binary',300,'2026-04-30 14:05:53','2026-04-30 14:05:53'),(2,'MULTI','Lorem Ipsum — Multi',300,'2026-04-30 14:05:53','2026-04-30 14:05:53');
/*!40000 ALTER TABLE `quizzes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_quiz_answers`
--

DROP TABLE IF EXISTS `user_quiz_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_quiz_answers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_quiz_id` bigint unsigned NOT NULL,
  `answer_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_quiz_answers_user_quiz_id_foreign` (`user_quiz_id`),
  KEY `user_quiz_answers_answer_id_foreign` (`answer_id`),
  CONSTRAINT `user_quiz_answers_answer_id_foreign` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_quiz_answers_user_quiz_id_foreign` FOREIGN KEY (`user_quiz_id`) REFERENCES `user_quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_quiz_answers`
--

LOCK TABLES `user_quiz_answers` WRITE;
/*!40000 ALTER TABLE `user_quiz_answers` DISABLE KEYS */;
INSERT INTO `user_quiz_answers` VALUES (1,1,46,'2026-05-01 08:18:39','2026-05-01 08:18:39'),(2,1,35,'2026-05-01 08:18:47','2026-05-01 08:18:47'),(3,1,31,'2026-05-01 08:20:27','2026-05-01 08:20:27'),(4,1,29,'2026-05-01 08:20:29','2026-05-01 08:20:29'),(5,1,50,'2026-05-01 08:20:30','2026-05-01 08:20:30'),(6,1,27,'2026-05-01 08:20:30','2026-05-01 08:20:30'),(7,1,38,'2026-05-01 08:20:31','2026-05-01 08:20:31'),(8,1,42,'2026-05-01 08:20:32','2026-05-01 08:20:32'),(9,1,60,'2026-05-01 08:20:33','2026-05-01 08:20:33'),(10,1,56,'2026-05-01 08:20:34','2026-05-01 08:20:34');
/*!40000 ALTER TABLE `user_quiz_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_quizzes`
--

DROP TABLE IF EXISTS `user_quizzes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_quizzes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BINARY',
  `time_left` int DEFAULT NULL,
  `total_questions` int unsigned NOT NULL DEFAULT '10',
  `question_ids` json DEFAULT NULL,
  `unanswered_count` int unsigned NOT NULL DEFAULT '0',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `score` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_quizzes_user_id_foreign` (`user_id`),
  CONSTRAINT `user_quizzes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_quizzes`
--

LOCK TABLES `user_quizzes` WRITE;
/*!40000 ALTER TABLE `user_quizzes` DISABLE KEYS */;
INSERT INTO `user_quizzes` VALUES (1,1,'MULTI',144,10,'[20, 16, 15, 14, 21, 13, 17, 18, 24, 23]',0,'2026-05-01 08:20:35',6,'2026-05-01 08:17:58','2026-05-01 08:20:35');
/*!40000 ALTER TABLE `user_quizzes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BINARY',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin',NULL,'admin@local.host',NULL,'$2y$10$QeisUdCmIRbAafMHbxFOUeIaGZGFhY1L7ytLpTgvuNsLe39y6eAbu',1,'MULTI',NULL,'2026-04-30 14:05:53','2026-04-30 14:09:44');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-01 12:51:57
