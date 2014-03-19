-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2013 at 02:42 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `asterisk_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_publish_date`
--

CREATE TABLE IF NOT EXISTS `fieldset_publish_date` (
  `fieldset_publish_date_id` int(11) NOT NULL AUTO_INCREMENT,
  `version_id` int(11) NOT NULL,
  `fieldset_config_id` int(11) NOT NULL,
  `index_first_date` datetime DEFAULT NULL,
  `index_last_date` datetime DEFAULT NULL,
  PRIMARY KEY (`fieldset_publish_date_id`),
  KEY `version_id` (`version_id`),
  KEY `fieldset_config_id` (`fieldset_config_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_publish_date_data`
--

CREATE TABLE IF NOT EXISTS `fieldset_publish_date_data` (
  `fieldset_publish_date_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_publish_date_id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `publish_date` datetime NOT NULL,
  `note` text,
  PRIMARY KEY (`fieldset_publish_date_data_id`),
  KEY `fieldset_publish_date_id` (`fieldset_publish_date_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_publish_date`
--
ALTER TABLE `fieldset_publish_date`
  ADD CONSTRAINT `fieldset_publish_date_ibfk_1` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_publish_date_ibfk_2` FOREIGN KEY (`fieldset_config_id`) REFERENCES `fieldset_config` (`fieldset_config_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fieldset_publish_date_data`
--
ALTER TABLE `fieldset_publish_date_data`
  ADD CONSTRAINT `fieldset_publish_date_data_ibfk_1` FOREIGN KEY (`fieldset_publish_date_id`) REFERENCES `fieldset_publish_date` (`fieldset_publish_date_id`) ON DELETE CASCADE ON UPDATE CASCADE;
