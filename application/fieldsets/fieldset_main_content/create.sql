-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2013 at 02:39 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `asterisk_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_main_content`
--

CREATE TABLE IF NOT EXISTS `fieldset_main_content` (
  `fieldset_main_content_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_config_id` int(11) NOT NULL,
  `version_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `body` longtext,
  PRIMARY KEY (`fieldset_main_content_id`),
  KEY `version_id` (`version_id`),
  KEY `fieldset_config_id` (`fieldset_config_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_main_content`
--
ALTER TABLE `fieldset_main_content`
  ADD CONSTRAINT `fieldset_main_content_ibfk_1` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_main_content_ibfk_2` FOREIGN KEY (`fieldset_config_id`) REFERENCES `fieldset_config` (`fieldset_config_id`) ON DELETE CASCADE ON UPDATE CASCADE;
