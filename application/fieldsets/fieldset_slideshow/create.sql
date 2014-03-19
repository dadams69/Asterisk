-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2013 at 02:47 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `asterisk_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_slideshow`
--

CREATE TABLE IF NOT EXISTS `fieldset_slideshow` (
  `fieldset_slideshow_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_config_id` int(11) NOT NULL,
  `version_id` int(11) NOT NULL,
  `fieldset_key` varchar(20) NOT NULL,
  PRIMARY KEY (`fieldset_slideshow_id`),
  KEY `fieldset_config_id` (`fieldset_config_id`),
  KEY `version_id` (`version_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_slideshow_mapping`
--

CREATE TABLE IF NOT EXISTS `fieldset_slideshow_mapping` (
  `fieldset_slideshow_id` int(11) NOT NULL,
  `fieldset_file_data_id` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  KEY `fieldset_slideshow_id` (`fieldset_slideshow_id`),
  KEY `fieldset_file_data_id` (`fieldset_file_data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_slideshow`
--
ALTER TABLE `fieldset_slideshow`
  ADD CONSTRAINT `fieldset_slideshow_ibfk_1` FOREIGN KEY (`fieldset_config_id`) REFERENCES `fieldset_config` (`fieldset_config_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_slideshow_ibfk_2` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fieldset_slideshow_mapping`
--
ALTER TABLE `fieldset_slideshow_mapping`
  ADD CONSTRAINT `fieldset_slideshow_mapping_ibfk_1` FOREIGN KEY (`fieldset_slideshow_id`) REFERENCES `fieldset_slideshow` (`fieldset_slideshow_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_slideshow_mapping_ibfk_2` FOREIGN KEY (`fieldset_file_data_id`) REFERENCES `fieldset_file_data` (`fieldset_file_data_id`) ON DELETE CASCADE ON UPDATE CASCADE;
