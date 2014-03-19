-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2013 at 02:49 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `asterisk_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_tags`
--

CREATE TABLE IF NOT EXISTS `fieldset_tags` (
  `fieldset_tags_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_config_id` int(11) NOT NULL,
  `version_id` int(11) NOT NULL,
  `fieldset_key` varchar(50) NOT NULL,
  `id_index` varchar(1000) NOT NULL,
  `name_index` varchar(1000) NOT NULL,
  `key_index` varchar(1000) NOT NULL,
  PRIMARY KEY (`fieldset_tags_id`),
  KEY `version_id` (`version_id`),
  KEY `fieldset_config_id` (`fieldset_config_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_tags_data`
--

CREATE TABLE IF NOT EXISTS `fieldset_tags_data` (
  `fieldset_tags_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(255) NOT NULL,
  `key` varchar(100) NOT NULL,
  `group_key` varchar(20) NOT NULL,
  PRIMARY KEY (`fieldset_tags_data_id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_tags_mapping`
--

CREATE TABLE IF NOT EXISTS `fieldset_tags_mapping` (
  `fieldset_tags_id` int(11) NOT NULL,
  `fieldset_tags_data_id` int(11) NOT NULL,
  KEY `fieldset_tags_id` (`fieldset_tags_id`),
  KEY `fieldset_tags_data_id` (`fieldset_tags_data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_tags`
--
ALTER TABLE `fieldset_tags`
  ADD CONSTRAINT `fieldset_tags_ibfk_1` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fieldset_tags_mapping`
--
ALTER TABLE `fieldset_tags_mapping`
  ADD CONSTRAINT `fieldset_tags_mapping_ibfk_1` FOREIGN KEY (`fieldset_tags_id`) REFERENCES `fieldset_tags` (`fieldset_tags_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_tags_mapping_ibfk_2` FOREIGN KEY (`fieldset_tags_data_id`) REFERENCES `fieldset_tags_data` (`fieldset_tags_data_id`) ON DELETE CASCADE ON UPDATE CASCADE;
