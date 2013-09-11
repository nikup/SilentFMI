-- phpMyAdmin SQL Dump
-- version 4.0.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Време на генериране: 27 юли 2013 в 21:51
-- Версия на сървъра: 5.5.25a
-- Версия на PHP: 5.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- БД: `calendar`
--

-- --------------------------------------------------------

--
-- Структура на таблица `rules`
--

CREATE TABLE IF NOT EXISTS `rules` (
  `userid` int(11) NOT NULL,
  `ruleid` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('RULE','EXCEPTION') NOT NULL DEFAULT 'RULE',
  `name` varchar(100) DEFAULT NULL,
  `periodtype` enum('DAILY','WEEKLY','MONTHLY','ONETIME') NOT NULL DEFAULT 'ONETIME',
  `perioddata` int(11) NOT NULL,
  `expiredate` int(11) NOT NULL DEFAULT '0',
  `priority` tinyint(4) NOT NULL DEFAULT '0',
  `fromtime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `active` enum('ACTIVE','DISABLED') NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`ruleid`),
  UNIQUE KEY `ruleid` (`ruleid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=102 ;

-- --------------------------------------------------------

--
-- Структура на таблица `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `pwdhash` varchar(100) NOT NULL,
  `fmiFN` int(9) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
