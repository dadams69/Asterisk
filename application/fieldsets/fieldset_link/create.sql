-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2013 at 02:37 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `asterisk_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_link`
--

CREATE TABLE IF NOT EXISTS `fieldset_link` (
  `fieldset_link_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_config_id` int(11) NOT NULL,
  `version_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `link_image` int(11) DEFAULT NULL,
  `description` text,
  `source` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`fieldset_link_id`),
  KEY `fieldset_config_id` (`fieldset_config_id`),
  KEY `version_id` (`version_id`),
  KEY `link_image` (`link_image`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_link`
--
ALTER TABLE `fieldset_link`
  ADD CONSTRAINT `fieldset_link_ibfk_1` FOREIGN KEY (`fieldset_config_id`) REFERENCES `fieldset_config` (`fieldset_config_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_link_ibfk_2` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_link_ibfk_3` FOREIGN KEY (`link_image`) REFERENCES `fieldset_file_data` (`fieldset_file_data_id`) ON DELETE SET NULL ON UPDATE CASCADE;
