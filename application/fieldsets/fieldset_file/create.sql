-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2013 at 02:36 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `asterisk_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_file`
--

CREATE TABLE IF NOT EXISTS `fieldset_file` (
  `fieldset_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_config_id` int(11) NOT NULL,
  `version_id` int(11) NOT NULL,
  `fieldset_key` varchar(50) NOT NULL,
  PRIMARY KEY (`fieldset_file_id`),
  KEY `version_id` (`version_id`),
  KEY `fieldset_config_id` (`fieldset_config_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_file_data`
--

CREATE TABLE IF NOT EXISTS `fieldset_file_data` (
  `fieldset_file_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(120) NOT NULL,
  `extension` varchar(4) NOT NULL,
  `title` varchar(120) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `credit` varchar(80) DEFAULT NULL,
  `keywords` varchar(250) DEFAULT NULL,
  `size` int(11) NOT NULL,
  `image_size_key` varchar(20) DEFAULT NULL,
  `image_height` int(11) DEFAULT NULL,
  `image_width` int(11) DEFAULT NULL,
  PRIMARY KEY (`fieldset_file_data_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_file_mapping`
--

CREATE TABLE IF NOT EXISTS `fieldset_file_mapping` (
  `fieldset_file_id` int(11) NOT NULL,
  `fieldset_file_data_id` int(11) NOT NULL,
  KEY `fieldset_file_id` (`fieldset_file_id`),
  KEY `fieldset_file_data_id` (`fieldset_file_data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_file`
--
ALTER TABLE `fieldset_file`
  ADD CONSTRAINT `fieldset_file_ibfk_1` FOREIGN KEY (`fieldset_config_id`) REFERENCES `fieldset_config` (`fieldset_config_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_file_ibfk_2` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fieldset_file_mapping`
--
ALTER TABLE `fieldset_file_mapping`
  ADD CONSTRAINT `fieldset_file_mapping_ibfk_1` FOREIGN KEY (`fieldset_file_id`) REFERENCES `fieldset_file` (`fieldset_file_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_file_mapping_ibfk_2` FOREIGN KEY (`fieldset_file_data_id`) REFERENCES `fieldset_file_data` (`fieldset_file_data_id`) ON DELETE CASCADE ON UPDATE CASCADE;
