--
-- Table structure for table `fieldset_testimonial`
--

CREATE TABLE IF NOT EXISTS `fieldset_testimonial` (
  `fieldset_testimonial_id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldset_config_id` int(11) NOT NULL,
  `version_id` int(11) NOT NULL,
  `testimonial` text,
  `source` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`fieldset_testimonial_id`),
  KEY `version_id` (`version_id`),
  KEY `fieldset_config_id` (`fieldset_config_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fieldset_testimonial`
--
ALTER TABLE `fieldset_testimonial`
  ADD CONSTRAINT `fieldset_testimonial_ibfk_1` FOREIGN KEY (`version_id`) REFERENCES `version` (`version_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fieldset_testimonial_ibfk_2` FOREIGN KEY (`fieldset_config_id`) REFERENCES `fieldset_config` (`fieldset_config_id`) ON DELETE CASCADE ON UPDATE CASCADE;
