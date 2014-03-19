-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2013 at 02:34 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `asterisk_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_calendar`
--

CREATE TABLE IF NOT EXISTS `fieldset_calendar` (
  `fieldset_calendar_id` int(11) NOT NULL AUTO_INCREMENT,
  `version_id` int(11) NOT NULL,
  `fieldset_config_id` int(11) NOT NULL,
  PRIMARY KEY (`fieldset_calendar_id`),
  KEY `version_id` (`version_id`),
  KEY `fieldset_config_id` (`fieldset_config_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_calendar_data`
--

CREATE TABLE IF NOT EXISTS `fieldset_calendar_data` (
  `fieldset_calendar_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_calendar_id` int(11) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `month` varchar(10) DEFAULT NULL,
  `day_of_month` varchar(10) DEFAULT NULL,
  `day_of_week` varchar(10) DEFAULT NULL,
  `hour` int(11) DEFAULT NULL,
  `minute` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  PRIMARY KEY (`fieldset_calendar_data_id`),
  KEY `fieldset_calendar_id` (`fieldset_calendar_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_calendar`
--
ALTER TABLE `fieldset_calendar`
  ADD CONSTRAINT `fieldset_calendar_ibfk_1` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_calendar_ibfk_2` FOREIGN KEY (`fieldset_config_id`) REFERENCES `fieldset_config` (`fieldset_config_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fieldset_calendar_data`
--
ALTER TABLE `fieldset_calendar_data`
  ADD CONSTRAINT `fieldset_calendar_data_ibfk_1` FOREIGN KEY (`fieldset_calendar_id`) REFERENCES `fieldset_calendar` (`fieldset_calendar_id`) ON DELETE CASCADE ON UPDATE CASCADE;
