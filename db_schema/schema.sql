CREATE TABLE `post_insights` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `page_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fetch_time` int(11) NOT NULL,
  `payload` mediumtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
