SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `ffueater`
--

CREATE TABLE IF NOT EXISTS `ffueater` (
 `id` int(11) NOT NULL auto_increment,
 `page` varchar(1000) collate utf8_unicode_ci NOT NULL,
 `data` longtext collate utf8_unicode_ci NOT NULL,
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