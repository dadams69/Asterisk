-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2013 at 02:35 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `asterisk_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_categories`
--

CREATE TABLE IF NOT EXISTS `fieldset_categories` (
  `fieldset_categories_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_config_id` int(11) NOT NULL,
  `version_id` int(11) NOT NULL,
  `fieldset_key` varchar(20) NOT NULL,
  `primary_fieldset_category_data_id` int(11) DEFAULT NULL,
  `id_index` varchar(1000) NOT NULL,
  `name_index` varchar(1000) NOT NULL,
  `key_index` varchar(1000) NOT NULL,
  PRIMARY KEY (`fieldset_categories_id`),
  KEY `version_id` (`version_id`),
  KEY `fieldset_config_id` (`fieldset_config_id`),
  KEY `primary_fieldset_category_data_id` (`primary_fieldset_category_data_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_categories_data`
--

CREATE TABLE IF NOT EXISTS `fieldset_categories_data` (
  `fieldset_categories_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_fieldset_categories_data_id` int(11) DEFAULT NULL,
  `category_name` varchar(255) NOT NULL,
  `key` varchar(100) NOT NULL,
  `group_key` varchar(20) NOT NULL,
  `last_modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rank` int(11) NOT NULL,
  PRIMARY KEY (`fieldset_categories_data_id`),
  KEY `parent_fieldset_categories_data_id` (`parent_fieldset_categories_data_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_categories_mapping`
--

CREATE TABLE IF NOT EXISTS `fieldset_categories_mapping` (
  `fieldset_categories_id` int(11) NOT NULL,
  `fieldset_categories_data_id` int(11) NOT NULL,
  KEY `fieldset_categories_id` (`fieldset_categories_id`),
  KEY `fieldset_categories_data_id` (`fieldset_categories_data_id`),
  KEY `fieldset_categories_id_2` (`fieldset_categories_id`),
  KEY `fieldset_categories_data_id_2` (`fieldset_categories_data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_categories`
--
ALTER TABLE `fieldset_categories`
  ADD CONSTRAINT `fieldset_categories_ibfk_1` FOREIGN KEY (`fieldset_config_id`) REFERENCES `fieldset_config` (`fieldset_config_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_categories_ibfk_2` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_categories_ibfk_4` FOREIGN KEY (`primary_fieldset_category_data_id`) REFERENCES `fieldset_categories_data` (`fieldset_categories_data_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `fieldset_categories_data`
--
ALTER TABLE `fieldset_categories_data`
  ADD CONSTRAINT `fieldset_categories_data_ibfk_1` FOREIGN KEY (`parent_fieldset_categories_data_id`) REFERENCES `fieldset_categories_data` (`fieldset_categories_data_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fieldset_categories_mapping`
--
ALTER TABLE `fieldset_categories_mapping`
  ADD CONSTRAINT `fieldset_categories_mapping_ibfk_1` FOREIGN KEY (`fieldset_categories_id`) REFERENCES `fieldset_categories` (`fieldset_categories_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_categories_mapping_ibfk_2` FOREIGN KEY (`fieldset_categories_data_id`) REFERENCES `fieldset_categories_data` (`fieldset_categories_data_id`) ON DELETE CASCADE ON UPDATE CASCADE;
