CREATE DATABASE kispests_new;
CREATE USER 'kispests_new'@'localhost' IDENTIFIED BY 'cNlBKQwq74kF';
GRANT ALL PRIVILEGES ON kispests_new * TO 'kispests_new'@'localhost';

USE kispests_new;

-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 25, 2015 at 12:07 AM
-- Server version: 5.5.41-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `kispests_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE IF NOT EXISTS `admin_users` (
  `username` varchar(50) COLLATE utf8_hungarian_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_hungarian_ci NOT NULL,
  `full_name` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `email` varchar(200) COLLATE utf8_hungarian_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `reg_date` datetime NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

INSERT INTO admin_users VALUES("admin",MD5("admin"),"Admin","admin@kispestse.hu",1,NULL,NOW());

--
-- Table structure for table `courts`
--

CREATE TABLE IF NOT EXISTS `courts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timeunits`
--

CREATE TABLE IF NOT EXISTS `timeunits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `court_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `from` time NOT NULL,
  `to` time NOT NULL,
  `price` int(11) NOT NULL,
  `comment` varchar(1000) DEFAULT NULL,
  `available` tinyint(4) NOT NULL,
  `reserved_by` varchar(255) DEFAULT NULL,
  `prereserved_expire` datetime DEFAULT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(50) COLLATE utf8_hungarian_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_hungarian_ci NOT NULL,
  `full_name` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `email` varchar(200) COLLATE utf8_hungarian_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8_hungarian_ci NOT NULL,
  `birthday` date DEFAULT NULL,
  `discount` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(3) unsigned NOT NULL,
  `activation_key` varchar(8) COLLATE utf8_hungarian_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `reg_date` datetime NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_transactions`
--

CREATE TABLE IF NOT EXISTS `users_transactions` (
  `username` varchar(50) NOT NULL,
  `value` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `object_id` int(11) DEFAULT NULL,
  `admin_username` varchar(255) DEFAULT NULL,
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Table structure for table `reservations`
--

CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timeunit_id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_hungarian_ci NOT NULL,
  `price` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `last_status_update` datetime NOT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;