--
-- Houses - Setup (1.0.0)
--
-- @package Coordinator\Modules\Houses
-- @author  Manuel Zavatta <manuel.zavatta@gmail.com>
-- @link    http://www.coordinator.it
--

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Table structure for table `houses__houses`
--

CREATE TABLE IF NOT EXISTS `houses__houses` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `street` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `number` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `internal` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `town` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `district` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `houses__houses__logs`
--

CREATE TABLE IF NOT EXISTS `houses__houses__logs` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fkObject` int(11) UNSIGNED NOT NULL,
  `fkUser` int(11) UNSIGNED DEFAULT NULL,
  `timestamp` int(11) UNSIGNED NOT NULL,
  `alert` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `event` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `properties_json` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fkObject` (`fkObject`),
  CONSTRAINT `houses__houses__logs_ibfk_1` FOREIGN KEY (`fkObject`) REFERENCES `houses__houses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `houses__houses__rooms`
--

CREATE TABLE IF NOT EXISTS `houses__houses__rooms` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `fkHouse` int(11) UNSIGNED NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fkHouse` (`fkHouse`),
  CONSTRAINT `houses__houses__rooms_ibfk_1` FOREIGN KEY (`fkHouse`) REFERENCES `houses__houses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `houses__houses__counters`
--

CREATE TABLE IF NOT EXISTS `houses__houses__counters` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `fkHouse` int(11) UNSIGNED NOT NULL,
  `fkCounter` int(11) UNSIGNED NOT NULL,
  `competence` double UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fkHouse` (`fkHouse`),
  KEY `fkCounter` (`fkCounter`),
  CONSTRAINT `houses__houses__counters_ibfk_1` FOREIGN KEY (`fkHouse`) REFERENCES `houses__houses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `houses__houses__counters_ibfk_2` FOREIGN KEY (`fkCounter`) REFERENCES `counters__counters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `houses__houses__join__users`
--

CREATE TABLE IF NOT EXISTS `houses__houses__join__users` (
  `fkHouse` int(11) UNSIGNED NOT NULL,
  `fkUser` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`fkHouse`,`fkUser`),
  KEY `fkHouse` (`fkHouse`),
  KEY `fkUser` (`fkUser`),
  CONSTRAINT `houses__houses__join__users_ibfk_1` FOREIGN KEY (`fkHouse`) REFERENCES `houses__houses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `houses__houses__join__users_ibfk_2` FOREIGN KEY (`fkUser`) REFERENCES `framework__users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Authorizations
--

INSERT IGNORE INTO `framework__modules__authorizations` (`id`,`fkModule`,`order`) VALUES
('houses-manage','houses',1),
('houses-usage','houses',2);

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------