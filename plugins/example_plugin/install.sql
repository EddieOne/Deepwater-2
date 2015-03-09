CREATE TABLE IF NOT EXISTS `testimonials` (
  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Testimonial id',
  `date` int(13) unsigned NOT NULL COMMENT 'unix time stamp',
  `name` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Name of person submiting testimonial.',
  `feedback` text CHARACTER SET utf8 NOT NULL COMMENT 'Feedback from the person.',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0 = unapproved, 1 = approved',
  PRIMARY KEY (`tid`),
  KEY `date` (`date`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;