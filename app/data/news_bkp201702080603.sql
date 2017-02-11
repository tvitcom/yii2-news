-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Фев 08 2017 г., 06:03
-- Версия сервера: 5.5.54-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.20

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `news`
--

-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1486088696),
('m130524_201442_init', 1486088702);

-- --------------------------------------------------------

--
-- Структура таблицы `person`
--

CREATE TABLE IF NOT EXISTS `person` (
  `id` bigint(21) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pass_hash` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `messenger` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` smallint(8) NOT NULL DEFAULT '10',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type_notify` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'all',
  `notify_about` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'newsadded',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `person`
--

INSERT INTO `person` (`id`, `username`, `auth_key`, `pass_hash`, `password_reset_token`, `email`, `messenger`, `status`, `created_at`, `updated_at`, `type_notify`, `notify_about`) VALUES
(1, 'admin', 'auth_adminkey', '$2y$13$ykIpe9SKG.wtJBcHpYXjEOXmvIgBwgUANoPL8do/oM6d3naZPdIn2', NULL, 'admin@adminmail.ruru', NULL, 10, '2000-01-01 00:00:00', '2017-02-06 09:06:50', 'all', 'newsadded'),
(2, 'user1', 'yo', '$2y$13$BHushAjWzwkNiftnRmFqCu06RUZpXcBmFt/fIVGsW0Lhq9Samz8Ne', NULL, 'user1@mail.ruru', NULL, 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'all', 'newsadded'),
(3, 'user2', 'CspKPL8bemNQZb1Lnr0TkbAk4VzPCCnY', '$2y$13$U8T24Ph.dLZK.8LxXbrcX.zwdssssDITQYmnDG8wbqE5nI06OUeCG', NULL, 'user2@mail.ruru', NULL, 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'all', 'newsadded'),
(4, 'user3', 'JotuirLYY0q8j7xn6AaHA9Ppk68Tx8CC', '$2y$13$E5W7Ec4AlUcHkOjcbbMqZ.3Tszki4DmeZEZAZ9hSnBmfQJ0nud7LG', NULL, 'user3@mail.ruru', NULL, 10, '2017-02-04 17:47:56', '2017-02-04 19:50:37', 'all', 'newsadded'),
(5, 'user4', '$2y$13$oeUl1Rx64cSLq3/J4PRQtexBQ', '$2y$13$VkHgZs4lOiDCusVMwmLv3.M5SBeESm3fAe8fS3VIpq7tmZLHH6ftu', NULL, 'user4@mail.ruru', NULL, 10, '2017-02-04 18:58:07', '2017-02-05 23:01:37', 'all', 'newsadded');

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` bigint(21) unsigned NOT NULL AUTO_INCREMENT,
  `author_id` bigint(21) unsigned DEFAULT NULL,
  `title` varchar(128) NOT NULL,
  `tags` varchar(128) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL,
  `source_uri` char(255) DEFAULT NULL,
  `picture_uri` char(255) DEFAULT NULL,
  `ratings` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_posts_person_idx` (`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='for news entries' AUTO_INCREMENT=1 ;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_person` FOREIGN KEY (`author_id`) REFERENCES `person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
