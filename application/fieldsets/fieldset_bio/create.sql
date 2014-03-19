-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2013 at 02:33 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `asterisk_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_bio`
--

CREATE TABLE IF NOT EXISTS `fieldset_bio` (
  `fieldset_bio_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_config_id` int(11) NOT NULL,
  `version_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `institution` varchar(50) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `degree_line` varchar(50) DEFAULT NULL,
  `bio` text,
  `full_image` int(11) DEFAULT NULL,
  `thumbnail` int(11) DEFAULT NULL,
  PRIMARY KEY (`fieldset_bio_id`),
  KEY `full_image` (`full_image`,`thumbnail`),
  KEY `fieldset_config_id` (`fieldset_config_id`),
  KEY `version_id` (`version_id`),
  KEY `thumbnail` (`thumbnail`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_bio`
--
ALTER TABLE `fieldset_bio`
  ADD CONSTRAINT `fieldset_bio_ibfk_1` FOREIGN KEY (`fieldset_config_id`) REFERENCES `fieldset_config` (`fieldset_config_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_bio_ibfk_2` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_bio_ibfk_3` FOREIGN KEY (`full_image`) REFERENCES `fieldset_file_data` (`fieldset_file_data_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_bio_ibfk_4` FOREIGN KEY (`thumbnail`) REFERENCES `fieldset_file_data` (`fieldset_file_data_id`) ON DELETE SET NULL ON UPDATE CASCADE;
