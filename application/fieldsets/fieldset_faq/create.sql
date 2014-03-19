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
-- Table structure for table `fieldset_faq`
--

CREATE TABLE IF NOT EXISTS `fieldset_faq` (
  `fieldset_faq_id` int(11) NOT NULL AUTO_INCREMENT,
  `version_id` int(11) NOT NULL,
  `fieldset_config_id` int(11) NOT NULL,
  PRIMARY KEY (`fieldset_faq_id`),
  KEY `version_id` (`version_id`),
  KEY `fieldset_config_id` (`fieldset_config_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_faq_data`
--

CREATE TABLE IF NOT EXISTS `fieldset_faq_data` (
  `fieldset_faq_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_faq_id` int(11) NOT NULL,
  `question` longtext,
  `answer` longtext,
  `rank` int(11) NOT NULL,
  PRIMARY KEY (`fieldset_faq_data_id`),
  KEY `fieldset_faq_id` (`fieldset_faq_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_faq`
--
ALTER TABLE `fieldset_faq`
  ADD CONSTRAINT `fieldset_faq_ibfk_1` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_faq_ibfk_2` FOREIGN KEY (`fieldset_config_id`) REFERENCES `fieldset_config` (`fieldset_config_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fieldset_faq_data`
--
ALTER TABLE `fieldset_faq_data`
  ADD CONSTRAINT `fieldset_faq_data_ibfk_1` FOREIGN KEY (`fieldset_faq_id`) REFERENCES `fieldset_faq` (`fieldset_faq_id`) ON DELETE CASCADE ON UPDATE CASCADE;
