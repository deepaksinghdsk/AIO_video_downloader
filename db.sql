-- --------------------------------------------------------
-- Sunucu:                       localhost
-- Sunucu sürümü:                10.3.16-MariaDB - mariadb.org binary distribution
-- Sunucu İşletim Sistemi:       Win64
-- HeidiSQL Sürüm:               9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- tablo yapısı dökülüyor aio_dl.contents
CREATE TABLE IF NOT EXISTS `contents` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `content_type` int(11) NOT NULL DEFAULT 0,
  `content_title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `content_description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `content_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content_text` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `content_opt` text COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- aio_dl.contents: ~23 rows (yaklaşık) tablosu için veriler indiriliyor
/*!40000 ALTER TABLE `contents` DISABLE KEYS */;
INSERT INTO `contents` (`ID`, `content_type`, `content_title`, `content_description`, `content_slug`, `content_text`, `content_opt`) VALUES
	(1, 0, 'Homepage', 'Homepage content', 'home', '', NULL),
	(2, 0, 'Terms of Service', 'Terms of service', 'tos', '<h1>Terms of Service</h1><p>You accepted this terms by using this website.</p>', NULL),
	(3, 0, 'Contact', 'Contact with us. Free!', 'contact', '<h1>Contact</h1>', NULL),
	(4, 1, 'Youtube Video Downloader', 'Youtube Video Downloader', 'youtube-video-downloader', '', NULL),
	(5, 1, 'Dailymotion Video Downloader', 'Dailymotion Video Downloader', 'dailymotion-video-downloader', '', NULL),
	(6, 1, 'Espn Video Downloader', 'Espn Video Downloader', 'espn-video-downloader', '', NULL),
	(7, 1, 'Odnoklassniki Video Downloader', 'Odnoklassniki Video Downloader', 'odnoklassniki-video-downloader', '', NULL),
	(8, 1, 'Mashable Video Downloader', 'Mashable Video Downloader', 'mashable-video-downloader', '', NULL),
	(9, 1, 'Tumblr Video Downloader', 'Tumblr Video Downloader', 'tumblr-video-downloader', '', NULL),
	(10, 1, 'Buzzfeed Video Downloader', 'Buzzfeed Video Downloader', 'buzzfeed-video-downloader', '', NULL),
	(11, 1, 'Instagram Video Downloader', 'Instagram Video Downloader', 'instagram-video-downloader', '', NULL),
	(12, 1, 'Liveleak Video Downloader', 'Liveleak Video Downloader', 'liveleak-video-downloader', '', NULL),
	(13, 1, 'Break Video Downloader', 'Break Video Downloader', 'break-video-downloader', '', NULL),
	(14, 1, 'Twitter Video Downloader', 'Twitter Video Downloader', 'twitter-video-downloader', '', NULL),
	(15, 1, 'Vimeo Video Downloader', 'Vimeo Video Downloader', 'vimeo-video-downloader', '', NULL),
	(16, 1, 'Soundcloud Music Downloader', 'Soundcloud Music Downloader', 'soundcloud-music-downloader', '', NULL),
	(17, 1, 'Izlesene Video Downloader', 'Izlesene Video Downloader', 'izlesene-video-downloader', '', NULL),
	(18, 1, 'Tiktok Video Downloader', 'Tiktok Video Downloader', 'tiktok-video-downloader', '', NULL),
	(19, 1, 'Bandcamp Music Downloader', 'Bandcamp Music Downloader', 'bandcamp-music-downloader', '', NULL),
	(20, 1, 'Imgur Video Downloader', 'Imgur Video Downloader', 'imgur-video-downloader', '', NULL),
	(21, 1, 'Imdb Video Downloader', 'Imdb Video Downloader', 'imdb-video-downloader', '', NULL),
	(22, 1, 'Flickr Video Downloader', 'Flickr Video Downloader', 'flickr-video-downloader', '', NULL),
	(23, 1, 'Facebook Video Downloader', 'Facebook Video Downloader', 'facebook-video-downloader', '', NULL),
	(24, 1, '9GAG Video Downloader', '9GAG Video Downloader', '9gag-video-downloader', NULL, NULL),
	(25, 1, 'TED Video Downloader', 'TED Video Downloader', 'ted-video-downloader', NULL, NULL),
	(26, 1, 'Vkontakte Video Downloader', 'Vkontakte Video Downloader', 'vk-video-downloader', NULL, NULL),
	(27, 1, 'Pinterest Video Downloader', 'Pinterest Video Downloader', 'pinterest-video-downloader', NULL, NULL),
	(28, 1, 'Likee Video Downloader', 'Likee Video Downloader', 'likee-video-downloader', NULL, NULL),
	(29, 1, 'Twitch Video Downloader', 'Twitch Video Downloader', 'twitch-clip-downloader', NULL, NULL),
	(30, 1, 'Blogger Video Downloader', 'Blogger Video Downloader', 'blogger-video-downloader', NULL, NULL),
	(31, 1, 'Reddit Video Downloader', 'Reddit Video Downloader', 'reddit-video-downloader', NULL, NULL),
	(32, 1, 'Douyin Video Downloader', 'Douyin Video Downloader', 'douyin-video-downloader', NULL, NULL),
	(33, 1, 'Kwai Video Downloader', 'Kwai Video Downloader', 'kwai-video-downloader', NULL, NULL),
	(34, 1, 'Linkedin Video Downloader', 'Linkedin Video Downloader', 'linkedin-video-downloader', NULL, NULL),
	(35, 1, 'Streamable Video Downloader', 'Streamable Video Downloader', 'streamable-video-downloader', NULL, NULL),
	(36, 1, 'Bitchute Video Downloader', 'Bitchute Video Downloader', 'bitchute-video-downloader', NULL, NULL),
	(37, 1, 'Akıllı TV Video Downloader', 'Akıllı TV Video Downloader', 'akillitv-video-downloader', NULL, NULL),
	(38, 1, 'Gaana Music Downloader', 'Gaana Music Downloader', 'gaana-music-downloader', NULL, NULL),
	(39, 1, 'Periscope Video Downloader', 'Periscope Video Downloader', 'periscope-video-downloader', NULL, NULL),
	(40, 1, 'Rumble Video Downloader', 'Rumble Video Downloader', 'rumble-video-downloader', NULL, NULL),
	(41, 1, 'Febspot Video Downloader', 'Febspot Video Downloader', 'febspot-video-downloader', NULL, NULL);
/*!40000 ALTER TABLE `contents` ENABLE KEYS */;

-- tablo yapısı dökülüyor aio_dl.downloads
CREATE TABLE IF NOT EXISTS `downloads` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `download_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `download_meta` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `download_links` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `download_source` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- tablo yapısı dökülüyor aio_dl.options
CREATE TABLE IF NOT EXISTS `options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `option_value` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- aio_dl.options: ~15 rows (yaklaşık) tablosu için veriler indiriliyor
/*!40000 ALTER TABLE `options` DISABLE KEYS */;
INSERT INTO `options` (`option_id`, `option_name`, `option_value`) VALUES
	(1, 'general_settings', '{\"url\":\"http:\\/\\/localhost\\/upload\",\"language\":\"en\",\"template\":\"material\",\"purchase_code\":\"0e355ea0-921c-4ca8-9294-963ea1b941a5\",\"version\":\"MzkyMjAuMTE5ODAxLjE2NTcxMi4xNTQxMDA=6\",\"title\":\"All in One Video Downloader\",\"description\":\"Meta Description\",\"author\":\"awasthi\",\"email\":\"admin@gmail.com\",\"download_suffix\":\"\",\"fingerprint\":\"44123b2d672caecb0c2529441d86b28c956043f2\"}'),
	(2, 'api_key.soundcloud', ''),
	(3, 'api_key.flickr', ''),
	(4, 'tracking_code', ''),
	(5, 'ads.1', ''),
	(6, 'ads.2', ''),
	(7, 'theme.general', '{"about":"true","ads":"true","tos":"true","contact":"true","social":"true","facebook":"facebook","twitter":"twitter","google":"google","youtube":"youtube","instagram":"instagram","logo_url":""}'),
	(8, 'theme.menu', ' [\r\n{"title":"Link","url":"#","target":"_self"},\r\n{"title":"Link","url":"#","target":"_blank"}\r\n] '),
	(9, 'gdpr_notice', '<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css"/> <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script> <script> window.addEventListener("load", function () { window.cookieconsent.initialise({ "palette": { "popup": { "background": "#252e39" }, "button": { "background": "#14a7d0" } }, "position": "bottom-right" }) }); </script>'),
	(10, 'api_key.recaptcha_public', ''),
	(11, 'api_key.recaptcha_private', ''),
	(12, 'api_key.aiovideodl', ''),
	(13, 'api_key.niche_office', ''),
	(14, 'api_key.bc_vc', ''),
	(15, 'ads.3', ''),
	(16, 'ads.4', '');
/*!40000 ALTER TABLE `options` ENABLE KEYS */;

-- tablo yapısı dökülüyor aio_dl.proxies
CREATE TABLE IF NOT EXISTS `proxies` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `port` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(14) DEFAULT NULL,
  `usage_count` bigint(20) NOT NULL DEFAULT 0,
  `banned` int(14) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- tablo yapısı dökülüyor aio_dl.users
CREATE TABLE IF NOT EXISTS `users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `user_pass` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_nicename` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_url` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_registered` datetime DEFAULT NULL,
  `user_activation_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_level` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- aio_dl.users: ~0 rows (yaklaşık) tablosu için veriler indiriliyor
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`ID`, `user_login`, `user_pass`, `user_email`, `user_nicename`, `user_url`, `user_activation_key`, `user_level`) VALUES
	(1, 'admin', '8cb2237d0679ca88db6464eac60da96345513964', 'admin@gmail.com', 'awasthi', NULL, NULL, 1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;