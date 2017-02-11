-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Фев 11 2017 г., 23:07
-- Версия сервера: 5.5
-- Версия PHP: 5.5.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+02:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `news`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `person`
--

INSERT INTO `person` (`id`, `username`, `auth_key`, `pass_hash`, `password_reset_token`, `email`, `messenger`, `status`, `created_at`, `updated_at`, `type_notify`, `notify_about`) VALUES
(1, 'admin', 'auth_adminkey', '$2y$13$ykIpe9SKG.wtJBcHpYXjEOXmvIgBwgUANoPL8do/oM6d3naZPdIn2', NULL, 'admin@adminmail.ruru', NULL, 10, '2000-01-01 00:00:00', '2017-02-06 09:06:50', 'all', 'newsadded');

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
