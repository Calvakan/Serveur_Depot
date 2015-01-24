-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 24 Janvier 2015 à 15:17
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `login`
--

-- --------------------------------------------------------

--
-- Structure de la table `authorized`
--

CREATE TABLE IF NOT EXISTS `authorized` (
  `id` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `authorized`
--

INSERT INTO `authorized` (`id`) VALUES
('df123456'),
('gh456789'),
('ty123345'),
('io789456'),
('zx456128'),
('bh159487'),
('as123782'),
('pl486759'),
('kr419561'),
('vg452159'),
('of258943'),
('ng456259');

-- --------------------------------------------------------

--
-- Structure de la table `binome`
--

CREATE TABLE IF NOT EXISTS `binome` (
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `id_depot` int(11) NOT NULL,
  `nom_binome` varchar(20) NOT NULL,
  `prenom_binome` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `depots`
--

CREATE TABLE IF NOT EXISTS `depots` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto-increment',
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `departement` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `annee` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `nom_prof` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `binome` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=40 ;

--
-- Contenu de la table `depots`
--

INSERT INTO `depots` (`id`, `nom`, `departement`, `annee`, `date`, `nom_prof`, `binome`) VALUES
(34, 'Le vrai plus tot', 'InfoTronique', '5A', '2014-11-12', 'prof', 1),
(33, 'Entre deux', 'InfoTronique', '3A', '2015-02-20', 'prof', 0),
(31, 'Le plus tot', 'Matériaux', '4A', '2015-01-31', 'prof', 0),
(32, 'Le plus tard', 'InfoTronique', '4A', '2015-04-10', 'prof', 0),
(39, 'ITC_42', 'InfoTronique', '4A', '2015-01-07', 'prof', 1),
(36, 'TestDownload', 'InfoTronique', '4A', '2015-03-28', 'prof', 0);

-- --------------------------------------------------------

--
-- Structure de la table `rapportsrendus`
--

CREATE TABLE IF NOT EXISTS `rapportsrendus` (
  `id_depot` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `rendu` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `rapportsrendus`
--

INSERT INTO `rapportsrendus` (`id_depot`, `nom`, `prenom`, `rendu`) VALUES
(32, 'Bernard', 'Marcel', 1),
(32, 'Denche', 'Samy', 1),
(36, 'Berte', 'Cédric', 1),
(36, 'Denche', 'Samy', 1),
(36, 'Bernard', 'Marcel', 1);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
  `user_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `departement` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `annee_formation` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'user''s activation status',
  `user_activation_hash` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s email verification hash string',
  `user_password_reset_hash` char(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s password reset code',
  `user_password_reset_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of the password reset request',
  `user_rememberme_token` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s remember-me cookie token',
  `user_failed_logins` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'user''s failed login attemps',
  `user_last_failed_login` int(10) DEFAULT NULL COMMENT 'unix timestamp of last failed login attempt',
  `user_registration_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_registration_ip` varchar(39) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0',
  `prof` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data' AUTO_INCREMENT=17 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_password_hash`, `user_email`, `departement`, `annee_formation`, `nom`, `prenom`, `user_active`, `user_activation_hash`, `user_password_reset_hash`, `user_password_reset_timestamp`, `user_rememberme_token`, `user_failed_logins`, `user_last_failed_login`, `user_registration_datetime`, `user_registration_ip`, `prof`) VALUES
(15, 'ty123345', '$2y$10$Ug/41a8prj5bW1bS1dcE.u8m0XwoF5aKPL9SgDrdta.qL9.9hueNa', 'cedric_berte@etu.u-bourgogne.fr', 'InfoTronique', '4A', 'Bernard', 'Marcel', 1, NULL, NULL, NULL, NULL, 0, NULL, '0000-00-00 00:00:00', '::1', 0),
(14, 'gh456789', '$2y$10$6Cf.VoETHYjulMpAEDUEreTAGZsnrLaGFJo9cE3IvglsTIJl49Ci6', 'cedric_berte@etu.u-bourgogne.fr', 'InfoTronique', '4A', 'Denche', 'Samy', 1, NULL, NULL, NULL, NULL, 0, NULL, '0000-00-00 00:00:00', '::1', 0),
(4, 'prof', '$2y$10$/QTIHnb38NxTup1H/vgrneWeHOFPhfe.deNN14hJx8ikP.L.6Ujua', 'berte.cedric@gmail.com', '', '', '', '', 1, NULL, NULL, NULL, NULL, 0, NULL, '2014-12-12 16:30:57', '::1', 1),
(13, 'df123456', '$2y$10$C7it5egcpbqXaSKRtXGPNu6h6pCO1xfrN4zphjZWL5WbDAYOTZMJe', 'cedric_berte@etu.u-bourgogne.fr', 'InfoTronique', '4A', 'Berte', 'Cédric', 1, NULL, NULL, NULL, NULL, 0, NULL, '0000-00-00 00:00:00', '::1', 0);
--
-- Base de données :  `test`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
