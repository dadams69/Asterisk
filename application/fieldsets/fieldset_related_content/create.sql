-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2013 at 03:03 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `asterisk_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_related_content`
--

CREATE TABLE IF NOT EXISTS `fieldset_related_content` (
  `fieldset_related_content_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_config_id` int(11) NOT NULL,
  `version_id` int(11) NOT NULL,
  `fieldset_key` varchar(20) NOT NULL,
  PRIMARY KEY (`fieldset_related_content_id`),
  KEY `fieldset_config_id` (`fieldset_config_id`),
  KEY `version_id` (`version_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_related_content_mapping`
--

CREATE TABLE IF NOT EXISTS `fieldset_related_content_mapping` (
  `fieldset_related_content_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  KEY `fieldset_related_content_id` (`fieldset_related_content_id`),
  KEY `content_id` (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_related_content`
--
ALTER TABLE `fieldset_related_content`
  ADD CONSTRAINT `fieldset_related_content_ibfk_1` FOREIGN KEY (`fieldset_config_id`) REFERENCES `fieldset_config` (`fieldset_config_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_related_content_ibfk_2` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fieldset_related_content_mapping`
--
ALTER TABLE `fieldset_related_content_mapping`
  ADD CONSTRAINT `fieldset_related_content_mapping_ibfk_1` FOREIGN KEY (`fieldset_related_content_id`) REFERENCES `fieldset_related_content` (`fieldset_related_content_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_related_content_mapping_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `content` (`content_id`) ON DELETE CASCADE ON UPDATE CASCADE;
