-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 16 Cze 2015, 10:07
-- Wersja serwera: 5.5.43
-- Wersja PHP: 5.5.25-1+deb.sury.org~precise+2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Baza danych: `crawler`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `advertisement`
--

CREATE TABLE IF NOT EXISTS `advertisement` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `source_name` varchar(10) COLLATE utf8_polish_ci NOT NULL,
  `source_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `district` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
  `address` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
  `author` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `area` float NOT NULL,
  `price_per_area` float NOT NULL,
  `price_per_meter` float NOT NULL,
  `rooms` tinyint(4) NOT NULL,
  `middleman` tinyint(1) NOT NULL,
  `added` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_polish_ci NOT NULL,

  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqie_source_id_pair` (`source_name`,`source_id`),
  KEY `source` (`source_name`),
  KEY `source_id` (`source_id`),
  KEY `phone` (`phone`),
  KEY `email` (`email`),
  KEY `rooms` (`rooms`),
  KEY `middleman` (`middleman`),
  KEY `district` (`district`),
  KEY `updated` (`updated`),
  KEY `added` (`added`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;
