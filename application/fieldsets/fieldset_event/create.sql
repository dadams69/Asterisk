
--
-- Table structure for table `fieldset_event`
--

CREATE TABLE IF NOT EXISTS `fieldset_event` (
  `fieldset_event_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_config_id` int(11) NOT NULL,
  `version_id` int(11) NOT NULL,
  `title` varchar(80) DEFAULT NULL,
  `description` text,
  `primary_image` int(11) DEFAULT NULL,
  `location` varchar(80) DEFAULT NULL,
  `cost` varchar(10) DEFAULT NULL,
  `primary_text` text,
  `primary_link` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`fieldset_event_id`),
  KEY `fieldset_config_id` (`fieldset_config_id`),
  KEY `version_id` (`version_id`),
  KEY `primary_image` (`primary_image`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_event`
--
ALTER TABLE `fieldset_event`
  ADD CONSTRAINT `fieldset_event_ibfk_1` FOREIGN KEY (`fieldset_config_id`) REFERENCES `fieldset_config` (`fieldset_config_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_event_ibfk_2` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_event_ibfk_3` FOREIGN KEY (`primary_image`) REFERENCES `fieldset_file_data` (`fieldset_file_data_id`) ON DELETE SET NULL ON UPDATE CASCADE;
