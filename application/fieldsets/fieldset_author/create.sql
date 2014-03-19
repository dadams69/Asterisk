-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2013 at 02:32 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `asterisk_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_author`
--

CREATE TABLE IF NOT EXISTS `fieldset_author` (
  `fieldset_author_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_config_id` int(11) NOT NULL,
  `version_id` int(11) NOT NULL,
  `fieldset_key` varchar(20) NOT NULL,
  PRIMARY KEY (`fieldset_author_id`),
  KEY `fieldset_config_id` (`fieldset_config_id`),
  KEY `version_id` (`version_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_author_data`
--

CREATE TABLE IF NOT EXISTS `fieldset_author_data` (
  `fieldset_author_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `key` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`fieldset_author_data_id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_author_mapping`
--

CREATE TABLE IF NOT EXISTS `fieldset_author_mapping` (
  `fieldset_author_mapping_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_author_id` int(11) NOT NULL,
  `fieldset_author_data_id` int(11) NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `rank` int(11) NOT NULL,
  PRIMARY KEY (`fieldset_author_mapping_id`),
  KEY `fieldset_author_data_id` (`fieldset_author_data_id`),
  KEY `fieldset_author_id` (`fieldset_author_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_author`
--
ALTER TABLE `fieldset_author`
  ADD CONSTRAINT `fieldset_author_ibfk_1` FOREIGN KEY (`fieldset_config_id`) REFERENCES `fieldset_config` (`fieldset_config_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_author_ibfk_2` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fieldset_author_mapping`
--
ALTER TABLE `fieldset_author_mapping`
  ADD CONSTRAINT `fieldset_author_mapping_ibfk_1` FOREIGN KEY (`fieldset_author_data_id`) REFERENCES `fieldset_author_data` (`fieldset_author_data_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_author_mapping_ibfk_3` FOREIGN KEY (`fieldset_author_id`) REFERENCES `fieldset_author` (`fieldset_author_id`) ON DELETE CASCADE ON UPDATE CASCADE;
