CREATE TABLE IF NOT EXISTS `#__apikeys` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `checked_out_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `domain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apikey` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userId` int(12) DEFAULT NULL,
  PRIMARY KEY (`id`)
) 