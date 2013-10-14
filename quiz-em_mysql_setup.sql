-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 12, 2013 at 04:09 PM
-- Server version: 5.5.32
-- PHP Version: 5.3.10-1ubuntu3.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `quiz`
--
CREATE DATABASE `quiz` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `quiz`;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quiz_id` int(10) unsigned NOT NULL,
  `file` longblob,
  `file_type` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `file_size` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `file_name` varchar(150) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`,`quiz_id`),
  KEY `property_images_FKIndex1` (`quiz_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=58 ;

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sort_order` int(11) DEFAULT NULL,
  `question` text,
  `type` varchar(100) DEFAULT NULL,
  `quiz_id` int(11) NOT NULL,
  `active` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Table structure for table `question_result`
--

CREATE TABLE IF NOT EXISTS `question_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quiz_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `response_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `correct` tinytext NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE IF NOT EXISTS `quiz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `message` varchar(200) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `annual` tinytext,
  `passing_score` int(11) DEFAULT NULL,
  `active` tinytext,
  `owner` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_result`
--

CREATE TABLE IF NOT EXISTS `quiz_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quiz_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `result_text` text NOT NULL,
  `score` decimal(10,0) NOT NULL,
  `pass` tinytext NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_staff_type`
--

CREATE TABLE IF NOT EXISTS `quiz_staff_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quiz_id` int(11) NOT NULL,
  `staff_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- Table structure for table `response`
--

CREATE TABLE IF NOT EXISTS `response` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `correct` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=181 ;

-- --------------------------------------------------------

--
-- Table structure for table `staff_type`
--

CREATE TABLE IF NOT EXISTS `staff_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `active` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_staff_type`
--

CREATE TABLE IF NOT EXISTS `user_staff_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `staff_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL,
  `display_name` varchar(150) NOT NULL,
  `admin` varchar(1) NOT NULL,
  `creator` varchar(1) DEFAULT NULL,
  `active` varchar(1) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
