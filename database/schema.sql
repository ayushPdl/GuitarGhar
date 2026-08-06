-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: guitarghar
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Import into your EXISTING hosting database (select it in phpMyAdmin first).
-- CREATE DATABASE is removed — shared hosts deny creating `guitarghar`.
--

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `guitar_builds`
--

DROP TABLE IF EXISTS `guitar_builds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guitar_builds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `shape` varchar(50) NOT NULL,
  `color` varchar(10) NOT NULL,
  `body_wood` varchar(50) NOT NULL,
  `neck_wood` varchar(50) NOT NULL,
  `fingerboard` varchar(50) NOT NULL,
  `pickups` varchar(50) NOT NULL,
  `bridge` varchar(50) NOT NULL,
  `hardware` varchar(50) NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `guitar_builds_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guitar_builds`
--

LOCK TABLES `guitar_builds` WRITE;
/*!40000 ALTER TABLE `guitar_builds` DISABLE KEYS */;
INSERT INTO `guitar_builds` (`id`, `user_id`, `shape`, `color`, `body_wood`, `neck_wood`, `fingerboard`, `pickups`, `bridge`, `hardware`, `saved_at`) VALUES (7,2,'strat','#1a3a6a','Alder','Maple','Maple','SSS','Synchronized Tremolo','Chrome','2026-08-04 06:29:18'),(9,2,'strat','#d4af37','Alder','Maple','Rosewood','SSS','Synchronized Tremolo','Chrome','2026-08-05 06:52:24'),(12,2,'strat','#f5f5f5','Alder','Maple','Rosewood','SSS','Synchronized Tremolo','Chrome','2026-08-05 07:23:51');
/*!40000 ALTER TABLE `guitar_builds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lesson_progress`
--

DROP TABLE IF EXISTS `lesson_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lesson_progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `lesson_id` varchar(10) NOT NULL,
  `completed` tinyint(1) DEFAULT 1,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_lesson` (`user_id`,`lesson_id`),
  CONSTRAINT `lesson_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_progress`
--

LOCK TABLES `lesson_progress` WRITE;
/*!40000 ALTER TABLE `lesson_progress` DISABLE KEYS */;
/*!40000 ALTER TABLE `lesson_progress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lessons`
--

DROP TABLE IF EXISTS `lessons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lessons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_id` varchar(10) NOT NULL,
  `level` enum('beginner','intermediate','advanced') NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `lesson_id` (`lesson_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lessons`
--

LOCK TABLES `lessons` WRITE;
/*!40000 ALTER TABLE `lessons` DISABLE KEYS */;
INSERT INTO `lessons` (`id`, `lesson_id`, `level`, `sort_order`, `title`, `description`, `video_url`, `created_at`) VALUES (1,'beg-01','beginner',1,'Your First Guitar Chords','Learn the very first chords every beginner needs: A, D, and E. We will cover proper finger placement and how to switch between them smoothly.',NULL,'2026-08-05 02:07:28'),(2,'beg-02','beginner',2,'How to Hold the Guitar & Pick','Learn the correct posture, how to hold the guitar, and the proper way to grip the pick for clean, comfortable playing.',NULL,'2026-08-05 02:07:28'),(3,'beg-03','beginner',3,'Open Chords: G, C and D Major','Master the essential open chords G, C, and D. These are the building blocks for thousands of popular songs.',NULL,'2026-08-05 02:07:28'),(4,'beg-04','beginner',4,'Strumming Patterns for Beginners','Learn basic down and up strumming patterns and how to keep a steady rhythm so your playing sounds musical right away.',NULL,'2026-08-05 02:07:28'),(5,'beg-05','beginner',5,'Reading Chord Diagrams & Tabs','Understand how to read chord diagrams and guitar tablature so you can learn songs on your own.',NULL,'2026-08-05 02:07:28'),(6,'beg-06','beginner',6,'The E Minor & A Minor Chords','Add the emotional minor chords to your toolkit and practice smooth transitions between major and minor shapes.',NULL,'2026-08-05 02:07:28'),(7,'beg-07','beginner',7,'Changing Chords Quickly','Practical exercises to help you switch between chords faster and with less buzzing or hesitation.',NULL,'2026-08-05 02:07:28'),(8,'beg-08','beginner',8,'Introduction to Fingerpicking','Start fingerpicking with simple patterns using your thumb and fingers for a fuller, warmer sound.',NULL,'2026-08-05 02:07:28'),(9,'beg-09','beginner',9,'Your First Song: 4-Chord Progression','Put everything together and play a full song using the classic G, D, Em, C progression.',NULL,'2026-08-05 02:07:28'),(10,'beg-10','beginner',10,'Power Chords & Palm Muting','Learn moveable power chords and palm muting to get a heavier, rock-ready sound.',NULL,'2026-08-05 02:07:28'),(11,'int-01','intermediate',1,'Barre Chords Masterclass','Learn the essential barre chord shapes (E and A shapes) and build the finger strength to play them cleanly up the neck.',NULL,'2026-08-05 02:07:28'),(12,'int-02','intermediate',2,'Pentatonic Scale: The Foundation','Master the minor pentatonic scale in the first position and start improvising meaningful solos.',NULL,'2026-08-05 02:07:28'),(13,'int-03','intermediate',3,'Soloing with the Blues Scale','Add the blues note to the pentatonic scale and learn classic blues licks and phrasing.',NULL,'2026-08-05 02:07:28'),(14,'int-04','intermediate',4,'Major Scale & CAGED System','Learn the major scale and the CAGED system to visualize the entire fretboard.',NULL,'2026-08-05 02:07:28'),(15,'int-05','intermediate',5,'Bending, Vibrato & Hammer-Ons','Develop expressive techniques: string bends, vibrato, hammer-ons, and pull-offs for vocal-like phrasing.',NULL,'2026-08-05 02:07:28'),(16,'int-06','intermediate',6,'Intermediate Strumming & Rhythm','Take your rhythm playing further with syncopation, muted strums, and more complex patterns.',NULL,'2026-08-05 02:07:28'),(17,'int-07','intermediate',7,'Chord Voicings & Inversions','Sick of the same open chords? Learn new voicings and inversions to make your comping more interesting.',NULL,'2026-08-05 02:07:28'),(18,'int-08','intermediate',8,'Triads Across the Neck','Learn major and minor triads in all inversions and use them to build solos and chord melodies.',NULL,'2026-08-05 02:07:28'),(19,'int-09','intermediate',9,'Alternate Picking Technique','Develop speed and precision with alternate picking exercises and scale sequences.',NULL,'2026-08-05 02:07:28'),(20,'int-10','intermediate',10,'Playing with a Metronome','Build rock-solid timing and groove by practicing scales, chords, and riffs with a metronome.',NULL,'2026-08-05 02:07:28'),(21,'adv-01','advanced',1,'Sweep Picking Fundamentals','Learn the fundamentals of sweep picking for clean arpeggios and fast, fluid runs across the neck.',NULL,'2026-08-05 02:07:28'),(22,'adv-02','advanced',2,'Modes of the Major Scale','Understand and apply the seven modes (Ionian, Dorian, Phrygian, Lydian, Mixolydian, Aeolian, Locrian).',NULL,'2026-08-05 02:07:28'),(23,'adv-03','advanced',3,'Advanced Chord Theory','Build extended chords: 7ths, 9ths, 11ths, and 13ths, and understand how to use them in progressions.',NULL,'2026-08-05 02:07:28'),(24,'adv-04','advanced',4,'Approach to Jazz Guitar','Learn jazz voicings, walking bass lines, and the language of jazz improvisation.',NULL,'2026-08-05 02:07:28'),(25,'adv-05','advanced',5,'Fretboard Mastery & Transposition','Connect all scale positions and learn to transpose any idea into any key instantly.',NULL,'2026-08-05 02:07:28'),(26,'adv-06','advanced',6,'Chord-Melody Arranging','Learn to combine melody and chords to arrange full solo guitar versions of songs.',NULL,'2026-08-05 02:07:28'),(27,'adv-07','advanced',7,'Tapping Techniques','Master two-handed tapping for dramatic, modern soloing effects.',NULL,'2026-08-05 02:07:28'),(28,'adv-08','advanced',8,'Blues & Jazz Turnarounds','Learn classic 12-bar turnaround progressions and how to improvise over them.',NULL,'2026-08-05 02:07:28'),(29,'adv-09','advanced',9,'Improvisation Strategies','Develop a complete approach to improvising over any chord progression with confidence.',NULL,'2026-08-05 02:07:28'),(30,'adv-10','advanced',10,'Performance & Stage Presence','Polish your performance skills, from gear setup to stage presence and dealing with nerves.',NULL,'2026-08-05 02:07:28');
/*!40000 ALTER TABLE `lessons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recommendations`
--

DROP TABLE IF EXISTS `recommendations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recommendations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `skill_level` varchar(50) NOT NULL,
  `genre` varchar(50) NOT NULL,
  `guitar_type` varchar(50) NOT NULL,
  `budget` varchar(50) NOT NULL,
  `extra_note` text DEFAULT NULL,
  `result` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `recommendations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recommendations`
--

LOCK TABLES `recommendations` WRITE;
/*!40000 ALTER TABLE `recommendations` DISABLE KEYS */;
INSERT INTO `recommendations` (`id`, `user_id`, `skill_level`, `genre`, `guitar_type`, `budget`, `extra_note`, `result`, `created_at`) VALUES (1,1,'Complete Beginner','Pop / Folk','Acoustic','NPR 10,000 to 25,000','','Sorry, could not get a recommendation. Please try again.','2026-05-26 03:02:54');
/*!40000 ALTER TABLE `recommendations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `created_at`) VALUES (1,'Ayush Poudel','test@gmail.com','$2y$10$nLIdN3x7MQdaGmhrvTp1lOY7SV2KY3V5QScXkjmHx4Ed1LoiNxl2a','2026-05-26 02:47:22'),(2,'Ayush Poudel','ab@gmail.com','$2y$10$RphvVU6TaXXYLqcE4mK.iuB9OJYmkcCcu7/ziynb60bHCA2VkHOkK','2026-06-23 07:27:15');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'guitarghar'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-05 18:19:53
