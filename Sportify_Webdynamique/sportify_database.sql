-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 01 juin 2025 à 16:00
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sportify_database`
--

-- --------------------------------------------------------

--
-- Structure de la table `coachs`
--

DROP TABLE IF EXISTS `coachs`;
CREATE TABLE IF NOT EXISTS `coachs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `specialite` varchar(100) DEFAULT NULL,
  `bureau` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `video_path` varchar(255) DEFAULT NULL,
  `activite` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `coachs`
--

INSERT INTO `coachs` (`id`, `user_id`, `specialite`, `bureau`, `photo`, `cv_path`, `video_path`, `activite`) VALUES
(5, 20, 'musculation', 'Salle A1', 'coach_alex.jpg', 'cv_alex.pdf', NULL, 'musculation'),
(12, 25, 'fitness', 'Salle B3', 'coach_chloe.jpg', 'cv_chloe.pdf', NULL, 'fitness'),
(11, 24, 'fitness', 'Salle B2', 'coach_lucas.jpg', 'cv_lucas.pdf', NULL, 'fitness'),
(7, 21, 'musculation', 'Salle A3', 'coach_sophie.jpg', 'cv_sophie.pdf', NULL, 'musculation'),
(10, 23, 'fitness', 'Salle B1', 'coach_emma.jpg', 'cv_emma.pdf', NULL, 'fitness'),
(9, 22, 'musculation', 'Salle A2', 'coach_leo.jpg', 'cv_leo.pdf', NULL, 'musculation'),
(13, 26, 'biking', 'Salle C1', 'coach_nathan.jpg', 'cv_nathan.pdf', NULL, 'biking'),
(14, 27, 'biking', 'Salle C2', 'coach_lina.jpg', 'cv_lina.pdf', NULL, 'biking'),
(15, 28, 'biking', 'Salle C3', 'coach_max.jpg', 'cv_max.pdf', NULL, 'biking'),
(16, 29, 'cardio-training', 'Salle D1', 'coach_eva.jpg', 'cv_eva.pdf', NULL, 'cardio-training'),
(17, 30, 'cardio-training', 'Salle D2', 'coach_paul.jpg', 'cv_paul.pdf', NULL, 'cardio-training'),
(18, 31, 'cardio-training', 'Salle D3', 'coach_julie.jpg', 'cv_julie.pdf', NULL, 'cardio-training'),
(19, 32, 'cours collectifs', 'Salle CC1', 'coach_elise.jpg', 'cv_elise.pdf', NULL, 'cours collectifs'),
(20, 33, 'cours collectifs', 'Salle CC2', 'coach_antoine.jpg', 'cv_antoine.pdf', NULL, 'cours collectifs'),
(21, 34, 'cours collectifs', 'Salle CC3', 'coach_camille.jpg', 'cv_camille.pdf', NULL, 'cours collectifs');

-- --------------------------------------------------------

--
-- Structure de la table `disponibilites`
--

DROP TABLE IF EXISTS `disponibilites`;
CREATE TABLE IF NOT EXISTS `disponibilites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `coach_id` int DEFAULT NULL,
  `jour` enum('lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche') DEFAULT NULL,
  `heure` time DEFAULT NULL,
  `disponible` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `coach_id` (`coach_id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `disponibilites`
--

INSERT INTO `disponibilites` (`id`, `coach_id`, `jour`, `heure`, `disponible`) VALUES
(2, 5, 'lundi', '10:00:00', 1),
(6, 5, 'vendredi', '16:00:00', 1),
(7, 14, 'mercredi', '14:30:00', 0),
(8, 17, 'samedi', '16:15:00', 1),
(10, 7, 'mardi', '09:00:00', 1),
(11, 7, 'mercredi', '11:00:00', 1),
(12, 9, 'jeudi', '14:00:00', 1),
(13, 9, 'vendredi', '10:30:00', 1),
(14, 10, 'samedi', '15:00:00', 1),
(15, 10, 'dimanche', '10:00:00', 1),
(16, 11, 'lundi', '13:00:00', 1),
(17, 11, 'mardi', '09:30:00', 0),
(18, 12, 'mercredi', '16:00:00', 1),
(19, 12, 'jeudi', '17:00:00', 1),
(20, 13, 'vendredi', '10:00:00', 1),
(21, 13, 'samedi', '14:00:00', 1),
(22, 14, 'dimanche', '12:30:00', 1),
(23, 14, 'lundi', '15:30:00', 1),
(24, 15, 'mardi', '09:00:00', 1),
(25, 15, 'mercredi', '13:00:00', 1),
(26, 16, 'jeudi', '08:30:00', 1),
(27, 16, 'vendredi', '11:00:00', 1),
(28, 17, 'samedi', '10:30:00', 1),
(29, 17, 'dimanche', '16:30:00', 1),
(30, 18, 'lundi', '13:00:00', 1),
(31, 18, 'mardi', '15:00:00', 1),
(32, 19, 'mercredi', '09:00:00', 1),
(33, 19, 'jeudi', '11:00:00', 1),
(34, 20, 'vendredi', '14:30:00', 1),
(35, 20, 'samedi', '12:00:00', 1),
(36, 21, 'dimanche', '11:30:00', 1),
(37, 21, 'lundi', '16:00:00', 1),
(40, 5, 'vendredi', '14:50:00', 0),
(41, 5, 'mardi', '09:30:00', 1),
(42, 5, 'mardi', '13:00:00', 1),
(43, 7, 'jeudi', '14:30:00', 1),
(44, 9, 'vendredi', '10:00:00', 1);

-- --------------------------------------------------------

--
-- Structure de la table `disponibilites_backup`
--

DROP TABLE IF EXISTS `disponibilites_backup`;
CREATE TABLE IF NOT EXISTS `disponibilites_backup` (
  `id` int NOT NULL DEFAULT '0',
  `coach_id` int DEFAULT NULL,
  `jour` enum('lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche') DEFAULT NULL,
  `heure` time DEFAULT NULL,
  `disponible` tinyint(1) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `disponibilites_backup`
--

INSERT INTO `disponibilites_backup` (`id`, `coach_id`, `jour`, `heure`, `disponible`) VALUES
(1, 1, 'lundi', '09:00:00', 1),
(2, 5, 'lundi', '10:00:00', 1),
(3, 1, 'mardi', '14:00:00', 1),
(4, 1, 'mercredi', '11:00:00', 1),
(5, 1, 'jeudi', '15:00:00', 1),
(6, 5, 'vendredi', '16:00:00', 1),
(7, 27, 'mercredi', '14:30:00', 0),
(8, 30, 'samedi', '16:15:00', 1),
(9, 37, 'mercredi', '20:20:00', 1),
(10, 21, '', '09:00:00', 0),
(11, 21, '', '11:00:00', 0),
(12, 22, '', '14:00:00', 1),
(13, 22, '', '10:30:00', 1),
(14, 23, '', '15:00:00', 0),
(15, 23, '', '10:00:00', 1),
(16, 24, '', '13:00:00', 1),
(17, 24, '', '09:30:00', 1),
(18, 25, '', '16:00:00', 0),
(19, 25, '', '17:00:00', 0),
(20, 26, '', '10:00:00', 1),
(21, 26, '', '14:00:00', 0),
(22, 27, '', '12:30:00', 1),
(23, 27, '', '15:30:00', 0),
(24, 28, '', '09:00:00', 1),
(25, 28, '', '13:00:00', 1),
(26, 29, '', '08:30:00', 1),
(27, 29, '', '11:00:00', 1),
(28, 30, '', '10:30:00', 1),
(29, 30, '', '16:30:00', 1),
(30, 31, '', '13:00:00', 0),
(31, 31, '', '15:00:00', 0),
(32, 32, '', '09:00:00', 1),
(33, 32, '', '11:00:00', 1),
(34, 33, '', '14:30:00', 0),
(35, 33, '', '12:00:00', 1),
(36, 34, '', '11:30:00', 1),
(37, 34, '', '16:00:00', 1),
(38, 37, '', '17:00:00', 1),
(39, 37, '', '10:00:00', 1),
(40, 20, 'vendredi', '14:50:00', 0),
(41, 20, 'mardi', '09:30:00', 0),
(42, 20, 'mardi', '13:00:00', 0),
(43, 21, 'jeudi', '14:30:00', 0),
(44, 22, 'vendredi', '10:00:00', 1);

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `expediteur_id` int NOT NULL,
  `destinataire_id` int NOT NULL,
  `message` text NOT NULL,
  `date_envoi` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `expediteur_id` (`expediteur_id`),
  KEY `destinataire_id` (`destinataire_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `expediteur_id`, `destinataire_id`, `message`, `date_envoi`) VALUES
(1, 2, 27, 'Coucou', '2025-06-01 04:14:36'),
(2, 27, 2, 'salut yanis', '2025-06-01 04:45:01'),
(3, 2, 14, 'salut', '2025-06-01 05:06:14'),
(4, 35, 30, 'coucou ca va ??', '2025-06-01 05:23:02'),
(5, 30, 35, 'oui et toi', '2025-06-01 05:36:31'),
(6, 11, 37, 'bonjour monsieur', '2025-06-01 11:53:40'),
(7, 2, 37, 'BONSOIR monsieur', '2025-06-01 11:55:56'),
(8, 37, 2, 'yop mon ptit pote', '2025-06-01 11:56:20');

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
CREATE TABLE IF NOT EXISTS `paiements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int DEFAULT NULL,
  `service` varchar(100) DEFAULT NULL,
  `montant` decimal(6,2) DEFAULT NULL,
  `statut` enum('valide','invalide') DEFAULT 'valide',
  `nom_carte` varchar(100) DEFAULT NULL,
  `numero_carte` varchar(20) DEFAULT NULL,
  `date_expiration` date DEFAULT NULL,
  `code_securite` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rdvs`
--

DROP TABLE IF EXISTS `rdvs`;
CREATE TABLE IF NOT EXISTS `rdvs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int DEFAULT NULL,
  `coach_id` int DEFAULT NULL,
  `date_rdv` date DEFAULT NULL,
  `heure_rdv` time DEFAULT NULL,
  `status` enum('confirmé','annulé') DEFAULT 'confirmé',
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `coach_id` (`coach_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `rdvs`
--

INSERT INTO `rdvs` (`id`, `client_id`, `coach_id`, `date_rdv`, `heure_rdv`, `status`) VALUES
(27, 2, 5, '2025-06-06', '14:50:00', 'confirmé'),
(24, 2, 14, '2025-06-08', '15:30:00', 'confirmé'),
(28, 2, 14, '2025-06-04', '14:30:00', 'confirmé'),
(25, 2, 5, '2025-06-03', '13:00:00', 'confirmé'),
(29, 2, 11, '2025-06-03', '09:30:00', 'confirmé');

-- --------------------------------------------------------

--
-- Structure de la table `services_salle`
--

DROP TABLE IF EXISTS `services_salle`;
CREATE TABLE IF NOT EXISTS `services_salle` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `role` enum('client','coach','admin') NOT NULL,
  `adresse` text,
  `carte_etudiant` varchar(50) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `activite` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`, `adresse`, `carte_etudiant`, `telephone`, `activite`) VALUES
(2, 'Chami\r\n', 'Yanis', 'yanis.chami@edu.ece.fr', '$2y$10$Xcjp6GxHE4ug3ZbVtaJv/OrGBKyCUM9Ro3i3QdpVVBpWW/c.4Sfm.', 'client', NULL, NULL, NULL, NULL),
(17, 'Admin', 'Test', 'admin@site.com', '$2y$10$PdpbtC37gOjKEsfacamsaOLieSekChhKYlnkpnl0qlyhh.vXjHCji', 'admin', NULL, NULL, NULL, NULL),
(11, 'Lady', 'Gaga', 'ladygaga@gmail.com', '$2y$10$7NzWq3bUBsjc7AMoi6yIwuKmciw25bx/VoTOgkb4CThqbShi6Y2LC', 'admin', NULL, NULL, NULL, NULL),
(24, 'Bernard', 'Lucas', 'lucas.fitness@example.com', '$2y$10$abcdefghejklmnopqrstuABCDEfGHJkLMnopqrs', 'coach', NULL, NULL, NULL, NULL),
(23, 'Thomas', 'Emma', 'emma.fitness@example.com', '$2y$10$abcdefghejklmnopqrstuABCDEfGHJkLMnopqrs', 'coach', NULL, NULL, NULL, NULL),
(20, 'Durand', 'Alex', 'alex.muscu@example.com', '$2y$10$BdFWmOocxRJJZwaj.yr4B.aegrLFcBhoyQ2ETIv2pu4V7I.badMy2', 'coach', NULL, NULL, NULL, NULL),
(21, 'Nguyen', 'Sophie', 'sophie.muscu@example.com', '$2y$10$abcdefghejklmnopqrstuABCDEfGHJkLMnopqrs', 'coach', NULL, NULL, NULL, NULL),
(22, 'Martin', 'Leo', 'leo.muscu@example.com', '$2y$10$abcdefghejklmnopqrstuABCDEfGHJkLMnopqrs', 'coach', NULL, NULL, NULL, NULL),
(25, 'Moreau', 'Chloe', 'chloe.fitness@example.com', '$2y$10$abcdefghejklmnopqrstuABCDEfGHJkLMnopqrs', 'coach', NULL, NULL, NULL, NULL),
(26, 'Petit', 'Nathan', 'nathan.biking@example.com', '$2y$10$abcdefghejklmnopqrstuABCDEfGHJkLMnopqrs', 'coach', NULL, NULL, NULL, NULL),
(27, 'Roux', 'Lina', 'lina.biking@example.com', '$2y$10$5Y8J9Pjl.X/KoVT8qyeI8e9ZU4pzsp9ZN6PYkYDg5fNDl7pBZhwHi', 'coach', NULL, NULL, NULL, NULL),
(28, 'Fontaine', 'Max', 'max.biking@example.com', '$2y$10$abcdefghejklmnopqrstuABCDEfGHJkLMnopqrs', 'coach', NULL, NULL, NULL, NULL),
(29, 'Garcia', 'Eva', 'eva.cardio@example.com', '$2y$10$abcdefghejklmnopqrstuABCDEfGHJkLMnopqrs', 'coach', NULL, NULL, NULL, NULL),
(30, 'Lopez', 'Paul', 'paul.cardio@example.com', '$2y$10$2huqscYREn3Fbr2QOka03uTEnRrgcnVUr7mXI5A966LAoBlvkar4.', 'coach', NULL, NULL, NULL, NULL),
(31, 'Dupuis', 'Julie', 'julie.cardio@example.com', '$2y$10$abcdefghejklmnopqrstuABCDEfGHJkLMnopqrs', 'coach', NULL, NULL, NULL, NULL),
(32, 'Martin', 'Elise', 'elise.collectif@example.com', '$2y$10$abcdefghjeklmnopqrstuABCDEFGHJKLmnopqrs', 'coach', NULL, NULL, NULL, NULL),
(33, 'Morel', 'Antoine', 'antoine.collectif@example.com', '$2y$10$abcdefghjeklmnopqrstuABCDEFGHJKLmnopqrs', 'coach', NULL, NULL, NULL, NULL),
(34, 'Robin', 'Camille', 'camille.collectif@example.com', '$2y$10$abcdefghjeklmnopqrstuABCDEFGHJKLmnopqrs', 'coach', NULL, NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
