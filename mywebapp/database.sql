-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 21, 2018 at 11:24 PM
-- Server version: 5.6.38
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `icd0007_lab_db`
--
CREATE DATABASE IF NOT EXISTS `icd0007_lab_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

USE `icd0007_lab_db`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text,
  `city` varchar(64) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `postal_code` varchar(15) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `is_admin` tinyint(1) unsigned DEFAULT '0' COMMENT 'A flag to indicate if a user is an admin (1 - admin, 0 - regular user)',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `profile_avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`,`telephone`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;