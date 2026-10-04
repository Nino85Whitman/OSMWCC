-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour osmwcc
CREATE DATABASE IF NOT EXISTS `osmwcc` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `osmwcc`;

-- Listage de la structure de table osmwcc. config
CREATE TABLE IF NOT EXISTS `config` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cheminAppli` varchar(50) NOT NULL,
  `destinataire` varchar(50) NOT NULL,
  `Autorized` int NOT NULL,
  `NbAutorized` int NOT NULL,
  `VersionOSMW` varchar(50) NOT NULL,
  `urlOSMW` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Listage des données de la table osmwcc.config : 1 rows
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` (`id`, `cheminAppli`, `destinataire`, `Autorized`, `NbAutorized`, `VersionOSMW`, `urlOSMW`) VALUES
	(1, '', 'email@mydomain.com', 1, 5, 'Version 24-11', '');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;

-- Listage de la structure de table osmwcc. hosts
CREATE TABLE IF NOT EXISTS `hosts` (
  `idhosts` tinyint NOT NULL AUTO_INCREMENT,
  `hosts_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `hosts_dns` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `hosts_api_key` varchar(1024) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`idhosts`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Listage des données de la table osmwcc.hosts : 3 rows
/*!40000 ALTER TABLE `hosts` DISABLE KEYS */;
INSERT INTO `hosts` (`idhosts`, `hosts_name`, `hosts_dns`, `hosts_api_key`) VALUES
	(1, 'Fgagod Windows', 'https://osmw.fgagod.fr/OSMW/', 'xxxxxxxxx'),
	(2, 'Fgagod Linux', 'https://osmw.fgagod.fr/OSMW/', 'yyyyyyyyyy'),
	(3, 'Fgagod VPS Linux', 'https://osmw.fgagod.fr/OSMW/', 'zzzzzzzzzzz');
/*!40000 ALTER TABLE `hosts` ENABLE KEYS */;

-- Listage de la structure de table osmwcc. users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(15) NOT NULL,
  `lastname` varchar(15) NOT NULL,
  `password` text NOT NULL,
  `privilege` int NOT NULL,
  `osAutorise` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Listage des données de la table osmwcc.users : 1 rows
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `firstname`, `lastname`, `password`, `privilege`, `osAutorise`) VALUES
	(1, 'Super', 'Admin', 'dc79f6a0f51567c820d0535f0c72a77cc5152258', 4, '');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
