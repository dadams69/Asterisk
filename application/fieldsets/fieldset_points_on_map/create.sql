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
-- Table structure for table `fieldset_points_on_map`
--

CREATE TABLE IF NOT EXISTS `fieldset_points_on_map` (
  `fieldset_points_on_map_id` int(11) NOT NULL AUTO_INCREMENT,
  `version_id` int(11) NOT NULL,
  `fieldset_config_id` int(11) NOT NULL,
  `fieldset_key` varchar(20) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`fieldset_points_on_map_id`),
  KEY `version_id` (`version_id`),
  KEY `fieldset_config_id` (`fieldset_config_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fieldset_points_on_map_data`
--

CREATE TABLE IF NOT EXISTS `fieldset_points_on_map_data` (
  `fieldset_points_on_map_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_points_on_map_id` int(11) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `description` text,
  `latitude` varchar(20) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`fieldset_points_on_map_data_id`),
  KEY `fieldset_points_on_map_id` (`fieldset_points_on_map_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_points_on_map`
--
ALTER TABLE `fieldset_points_on_map`
  ADD CONSTRAINT `fieldset_points_on_map_ibfk_1` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_points_on_map_ibfk_2` FOREIGN KEY (`fieldset_config_id`) REFERENCES `fieldset_config` (`fieldset_config_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fieldset_points_on_map_data`
--
ALTER TABLE `fieldset_points_on_map_data`
  ADD CONSTRAINT `fieldset_points_on_map_data_ibfk_1` FOREIGN KEY (`fieldset_points_on_map_id`) REFERENCES `fieldset_points_on_map` (`fieldset_points_on_map_id`) ON DELETE CASCADE ON UPDATE CASCADE;
