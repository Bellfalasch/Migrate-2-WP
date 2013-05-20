-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `migrate_content`
--

CREATE TABLE IF NOT EXISTS `migrate_content` (
 `id` int(11) NOT NULL auto_increment,
 `page` varchar(1000) collate utf8_unicode_ci NOT NULL,
 `html` longtext collate utf8_unicode_ci NOT NULL,
 `site` tinyint(4) NOT NULL,
 `content` longtext collate utf8_unicode_ci,
 `clean` longtext collate utf8_unicode_ci,
 `wp_url` varchar(100) collate utf8_unicode_ci default NULL,
 `wp_slug` varchar(50) collate utf8_unicode_ci default NULL,
 `wp_postid` int(11) NOT NULL default '0',
 `wp_guid` varchar(100) collate utf8_unicode_ci default NULL,
 PRIMARY KEY  (`id`),
 KEY `site` (`site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Table structure for table `migrate_users`
--

CREATE TABLE IF NOT EXISTS `migrate_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `mail` varchar(255) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `migrate_users`
--

INSERT INTO `migrate_users` (`id`, `name`, `mail`, `username`, `password`, `lastlogin`, `level`) VALUES
(1, NULL, 'test@test.no', 'Nils', 'pills', '2013-05-13 13:37:00', 2);