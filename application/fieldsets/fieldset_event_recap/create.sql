
--
-- Table structure for table `fieldset_event_recap`
--

CREATE TABLE IF NOT EXISTS `fieldset_event_recap` (
  `fieldset_event_recap_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_config_id` int(11) NOT NULL,
  `version_id` int(11) NOT NULL,
  `show_recap` tinyint(4) DEFAULT false,
  `description` text,
  `vimeo_id` varchar(50) DEFAULT NULL,
  `audio` varchar(50) DEFAULT NULL,
  `link` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`fieldset_event_recap_id`),
  KEY `fieldset_config_id` (`fieldset_config_id`),
  KEY `version_id` (`version_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Table structure for table `fieldset_slideshow_mapping`
--

CREATE TABLE IF NOT EXISTS `fieldset_event_recap_mapping` (
  `fieldset_event_recap_id` int(11) NOT NULL,
  `fieldset_file_data_id` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  KEY `fieldset_event_recap_id` (`fieldset_event_recap_id`),
  KEY `fieldset_file_data_id` (`fieldset_file_data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_event_recap`
--
ALTER TABLE `fieldset_event_recap`
  ADD CONSTRAINT `fieldset_event_recap_ibfk_1` FOREIGN KEY (`fieldset_config_id`) REFERENCES `fieldset_config` (`fieldset_config_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_event_recap_ibfk_2` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fieldset_event_recap_mapping`
--
ALTER TABLE `fieldset_event_recap_mapping`
  ADD CONSTRAINT `fieldset_event_recap_mapping_ibfk_1` FOREIGN KEY (`fieldset_event_recap_id`) REFERENCES `fieldset_event_recap` (`fieldset_event_recap_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_event_recap_mapping_ibfk_2` FOREIGN KEY (`fieldset_file_data_id`) REFERENCES `fieldset_file_data` (`fieldset_file_data_id`) ON DELETE CASCADE ON UPDATE CASCADE;
