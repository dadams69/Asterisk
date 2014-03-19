-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2013 at 02:44 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `asterisk_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_publishing`
--

CREATE TABLE IF NOT EXISTS `fieldset_publishing` (
  `fieldset_publishing_id` int(11) NOT NULL AUTO_INCREMENT,
  `version_id` int(11) NOT NULL,
  `fieldset_config_id` int(11) NOT NULL,
  `active` tinyint(4) DEFAULT NULL,
  `publish_date` datetime DEFAULT NULL,
  `expiration_date` datetime DEFAULT NULL,
  `key` varchar(128) DEFAULT NULL,
  `package` varchar(128) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL,
  PRIMARY KEY (`fieldset_publishing_id`),
  KEY `version_id` (`version_id`),
  KEY `fieldset_config_id` (`fieldset_config_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_publishing`
--
ALTER TABLE `fieldset_publishing`
  ADD CONSTRAINT `fieldset_publishing_ibfk_1` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_publishing_ibfk_2` FOREIGN KEY (`fieldset_config_id`) REFERENCES `fieldset_config` (`fieldset_config_id`) ON DELETE CASCADE ON UPDATE CASCADE;
