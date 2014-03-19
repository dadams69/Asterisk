-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2013 at 02:48 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `asterisk_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_summary`
--

CREATE TABLE IF NOT EXISTS `fieldset_summary` (
  `fieldset_summary_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_config_id` int(11) NOT NULL,
  `version_id` int(11) NOT NULL,
  `short_description` text,
  `quote` text,
  `quote_attribution` varchar(120) DEFAULT NULL,
  `image_size_1` int(11) DEFAULT NULL,
  `image_size_2` int(11) DEFAULT NULL,
  PRIMARY KEY (`fieldset_summary_id`),
  KEY `fieldset_config_id` (`fieldset_config_id`),
  KEY `version_id` (`version_id`),
  KEY `image_size_1` (`image_size_1`),
  KEY `image_size_2` (`image_size_2`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_summary`
--
ALTER TABLE `fieldset_summary`
  ADD CONSTRAINT `fieldset_summary_ibfk_1` FOREIGN KEY (`fieldset_config_id`) REFERENCES `fieldset_config` (`fieldset_config_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_summary_ibfk_2` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_summary_ibfk_3` FOREIGN KEY (`image_size_1`) REFERENCES `fieldset_file_data` (`fieldset_file_data_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_summary_ibfk_4` FOREIGN KEY (`image_size_2`) REFERENCES `fieldset_file_data` (`fieldset_file_data_id`) ON DELETE SET NULL ON UPDATE CASCADE;
